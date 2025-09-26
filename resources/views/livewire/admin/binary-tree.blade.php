<div class="py-6 lg:py-8 min-h-screen">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Binary Tree Structure</h2>

                <!-- Search Box -->
                <div class="flex items-center space-x-2">
                    <input id="search-node" type="text" placeholder="Search by name..."
                        class="border px-3 py-2 rounded-lg w-64 focus:ring focus:border-blue-400" />
                    <button id="clear-search"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Clear</button>
                </div>
            </div>

            <div class="relative">
                <!-- Loading Overlay -->
                <div wire:loading class="absolute inset-0 bg-white/75 flex items-center justify-center z-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-200 border-t-blue-500"></div>
                </div>

                <!-- Tree Container -->
                <div class="p-4 sm:p-6 bg-slate-50/50">
                    <div id="binary-tree-container"
                        class="w-full h-[400px] sm:h-[500px] lg:h-[600px] rounded-lg bg-white" wire:ignore></div>
                </div>

                <!-- Zoom Controls -->
                <div class="absolute bottom-6 right-6 flex space-x-2">
                    <button onclick="zoomIn()"
                        class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                    <button onclick="zoomOut()"
                        class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                        </svg>
                    </button>
                    <button onclick="resetZoom()"
                        class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://d3js.org/d3.v7.min.js"></script>
        <script>
            let svg, currentZoom, initialTransform, g, root;

            function initBinaryTree(data) {
                if (!data) return;

                // Clear previous tree
                d3.select("#binary-tree-container").html("");

                const width = document.getElementById('binary-tree-container').offsetWidth;
                const height = 600;
                const margin = { top: 60, right: 20, bottom: 60, left: 20 };

                svg = d3.select("#binary-tree-container")
                    .append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .attr("class", "bg-gray-50 rounded-lg");

                g = svg.append("g");

                const stratify = d3.stratify()
                    .id(d => d.id)
                    .parentId(d => d.parentId);

                root = stratify(data).sum(d => d.value || 1);

                const fixedWidth = 10000;
                const levelHeight = 150;

                const treeLayout = d3.tree()
                    .size([fixedWidth, root.height * levelHeight])
                    .separation((a, b) => a.parent === b.parent ? 1.5 : 2.5);

                treeLayout(root);

                const centerX = width / 2 - root.x;
                g.attr("transform", `translate(${centerX},${margin.top})`);

                // Links
                g.selectAll(".link")
                    .data(root.links())
                    .join("path")
                    .attr("class", "link")
                    .attr("d", d => `M${d.source.x},${d.source.y + 30}L${d.target.x},${d.target.y - 30}`)
                    .attr("fill", "none")
                    .attr("stroke", "#94a3b8")
                    .attr("stroke-width", 2)
                    .attr("marker-end", "url(#arrowhead)");

                svg.append("defs").append("marker")
                    .attr("id", "arrowhead")
                    .attr("viewBox", "0 -5 10 10")
                    .attr("refX", 8)
                    .attr("refY", 0)
                    .attr("markerWidth", 8)
                    .attr("markerHeight", 8)
                    .attr("orient", "auto")
                    .append("path")
                    .attr("d", "M0,-5L10,0L0,5")
                    .attr("fill", "#94a3b8");

                // Nodes
                const nodes = g.selectAll(".node")
                    .data(root.descendants())
                    .join("g")
                    .attr("class", "node")
                    .attr("transform", d => `translate(${d.x},${d.y})`);

                nodes.append("rect")
                    .attr("x", -60)
                    .attr("y", -30)
                    .attr("width", 120)
                    .attr("height", 60)
                    .attr("rx", 4)
                    .attr("fill", d => d.data.status === 'verified' ? '#ecfdf5' : '#fef2f2')
                    .attr("stroke", d => d.data.status === 'verified' ? '#10b981' : '#ef4444')
                    .attr("stroke-width", 2)
                    .attr("class", "node-rect transition-colors duration-300");

                nodes.append("text")
                    .attr("y", -35)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-[10px] font-medium text-gray-400")
                    .text(d => d.data.position === 'left' ? 'Left' : d.data.position === 'right' ? 'Right' : '');

                const labels = nodes.append("g").attr("class", "label");

                labels.append("text")
                    .attr("dy", "-0.5em")
                    .attr("text-anchor", "middle")
                    .attr("class", "text-[10px] font-medium")
                    .attr("fill", d => d.data.position === 'left' ? '#3b82f6' : d.data.position === 'right' ? '#8b5cf6' : '#6b7280')
                    .text(d => d.data.token ? '#' + d.data.token : '');

                labels.append("text")
                    .attr("dy", "0.5em")
                    .attr("text-anchor", "middle")
                    .attr("class", "text-[12px] font-medium text-gray-900")
                    .text(d => d.data.name)
                    .call(wrap, 100);

                function wrap(text, width) {
                    text.each(function () {
                        const text = d3.select(this);
                        const words = text.text().split(/\s+/).reverse();
                        let word, line = [], lineNumber = 0;
                        const lineHeight = 1.1, y = text.attr("y"), dy = parseFloat(text.attr("dy"));
                        let tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
                        while (word = words.pop()) {
                            line.push(word);
                            tspan.text(line.join(" "));
                            if (tspan.node().getComputedTextLength() > width) {
                                line.pop();
                                tspan.text(line.join(" "));
                                line = [word];
                                tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
                            }
                        }
                    });
                }

                // Zoom
                currentZoom = d3.zoom()
                    .scaleExtent([0.3, 2])
                    .on("zoom", (event) => g.attr("transform", event.transform));

                svg.call(currentZoom);

                // Initial fit
                const bounds = g.node().getBBox();
                const scale = Math.min(width / bounds.width, height / bounds.height) * 0.9;
                initialTransform = d3.zoomIdentity
                    .translate(width / 2 - bounds.x * scale - bounds.width * scale / 2, height / 2 - bounds.y * scale - bounds.height * scale / 2)
                    .scale(scale);

                svg.transition().duration(1000).call(currentZoom.transform, initialTransform);
            }

            // Zoom helpers
            function zoomIn() { if (svg) svg.transition().duration(300).call(currentZoom.scaleBy, 1.2); }
            function zoomOut() { if (svg) svg.transition().duration(300).call(currentZoom.scaleBy, 0.8); }
            function resetZoom() { if (svg) svg.transition().duration(500).call(currentZoom.transform, initialTransform); }

            // 🔍 Search Feature
            function searchNode(name) {
                const nodes = g.selectAll(".node");
                let foundNode = null;

                nodes.select("rect")
                    .attr("stroke", d => {
                        const match = name && d.data.name.toLowerCase().includes(name.toLowerCase());
                        if (match) foundNode = d;
                        return match ? "#2563eb" : (d.data.status === 'verified' ? '#10b981' : '#ef4444');
                    })
                    .attr("stroke-width", d => name && d.data.name.toLowerCase().includes(name.toLowerCase()) ? 4 : 2);

                // Auto zoom to found node
                if (foundNode) {
                    const scale = 1.2;
                    const x = -foundNode.x * scale + svg.attr("width") / 2;
                    const y = -foundNode.y * scale + svg.attr("height") / 2;
                    svg.transition().duration(750).call(currentZoom.transform, d3.zoomIdentity.translate(x, y).scale(scale));
                }
            }

            document.getElementById("search-node").addEventListener("input", e => searchNode(e.target.value));
            document.getElementById("clear-search").addEventListener("click", () => {
                document.getElementById("search-node").value = "";
                searchNode("");
                resetZoom();
            });

            document.addEventListener('DOMContentLoaded', function () {
                const treeData = @json($treeData);
                if (treeData) initBinaryTree(treeData);
            });

            window.addEventListener('resize', function () {
                const treeData = @json($treeData);
                if (treeData) initBinaryTree(treeData);
            });
        </script>
    @endpush
</div>
