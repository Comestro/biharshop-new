<div class="py-6 lg:py-8 min-h-screen">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-200">
                <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Binary Tree Structure</h2>
            </div>

            <div class="relative">
                <!-- Loading Overlay -->
                <div wire:loading class="absolute inset-0 bg-white/75 flex items-center justify-center z-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-200 border-t-blue-500"></div>
                </div>

                <!-- Tree Container -->
                <div class="p-4 sm:p-6 bg-slate-50/50">
                    <div id="binary-tree-container"
                         class="w-full h-[400px] sm:h-[500px] lg:h-[600px] rounded-lg bg-white"
                         wire:ignore></div>
                </div>

                <!-- Zoom Controls -->
                <div class="absolute bottom-6 right-6 flex space-x-2">
                    <button onclick="zoomIn()"
                            class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                    <button onclick="zoomOut()"
                            class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                        </svg>
                    </button>
                    <button onclick="resetZoom()"
                            class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>
        let svg, currentZoom, initialTransform;

        function initBinaryTree(data) {
            if (!data) return;

            // Clear previous tree
            d3.select("#binary-tree-container").html("");

            const width = document.getElementById('binary-tree-container').offsetWidth;
            const height = 600;
            const margin = {top: 60, right: 20, bottom: 60, left: 20};

            // Create the SVG container
            svg = d3.select("#binary-tree-container")
                .append("svg")
                .attr("width", width)
                .attr("height", height)
                .attr("class", "bg-gray-50 rounded-lg");

            const g = svg.append("g");

            // Create hierarchy using stratify
            const stratify = d3.stratify()
                .id(d => d.id)
                .parentId(d => d.parentId);

            // Generate the hierarchy
            const root = stratify(data)
                .sum(d => d.value || 1);

            // Improved tree layout with better spacing
            const treeLayout = d3.tree()
                .size([width - margin.left - margin.right, height - margin.top - margin.bottom])
                .separation((a, b) => a.parent === b.parent ? 2 : 3); // Increase horizontal spacing

            // Apply layout
            treeLayout(root);

            // Center the tree
            const centerX = width / 2 - root.x;
            g.attr("transform", `translate(${centerX},${margin.top})`);

            // Links with straight lines and improved visibility
            const links = g.selectAll(".link")
                .data(root.links())
                .join("path")
                .attr("class", "link")
                .attr("d", d => {
                    const sourceX = d.source.x;
                    const sourceY = d.source.y;
                    const targetX = d.target.x;
                    const targetY = d.target.y;

                    // Draw line from bottom of parent to top of child
                    return `M${sourceX},${sourceY + 30}L${targetX},${targetY - 30}`;
                })
                .attr("fill", "none")
                .attr("stroke", "#94a3b8")  // Darker line color
                .attr("stroke-width", 2)
                .attr("marker-end", "url(#arrowhead)");

            // Add arrow marker for direction indication
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

            // Enhanced nodes with better positioning
            const nodes = g.selectAll(".node")
                .data(root.descendants())
                .join("g")
                .attr("class", d => `node ${d.data.position || ''}`)  // Add position class
                .attr("transform", d => `translate(${d.x},${d.y})`);

            // Node rectangles with position indicators
            nodes.append("rect")
                .attr("x", -60)
                .attr("y", -30)
                .attr("width", 120)
                .attr("height", 60)
                .attr("rx", 4)
                .attr("fill", d => {
                    if (d.data.status === 'empty') return '#f8fafc';
                    return d.data.status === 'verified' ? '#ecfdf5' : '#fef2f2';
                })
                .attr("stroke", d => {
                    if (d.data.status === 'empty') return '#e2e8f0';
                    return d.data.status === 'verified' ? '#10b981' : '#ef4444';
                })
                .attr("stroke-width", 2)
                .attr("class", "transition-colors duration-300 hover:stroke-indigo-500");

            // Position indicator (Left/Right)
            nodes.append("text")
                .attr("y", -35)
                .attr("text-anchor", "middle")
                .attr("class", "text-[10px] font-medium text-gray-400")
                .text(d => {
                    if (d.data.position === 'left') return 'Left';
                    if (d.data.position === 'right') return 'Right';
                    return '';
                });

            // Enhanced label styles with better positioning
            const labels = nodes.append("g")
                .attr("class", "label");

            // Member ID with position color
            labels.append("text")
                .attr("dy", "-0.5em")
                .attr("text-anchor", "middle")
                .attr("class", "text-[10px] font-medium")
                .attr("fill", d => {
                    if (d.data.position === 'left') return '#3b82f6';
                    if (d.data.position === 'right') return '#8b5cf6';
                    return '#6b7280';
                })
                .text(d => d.data.token ? '#' + d.data.token : '');

            // Member name with better visibility
            labels.append("text")
                .attr("dy", "0.5em")
                .attr("text-anchor", "middle")
                .attr("class", "text-[12px] font-medium text-gray-900")
                .text(d => d.data.name)
                .call(wrap, 100); // Increased width for better text wrapping in rectangles

            // Text wrapping function
            function wrap(text, width) {
                text.each(function() {
                    const text = d3.select(this);
                    const words = text.text().split(/\s+/).reverse();
                    let word;
                    let line = [];
                    let lineNumber = 0;
                    const lineHeight = 1.1;
                    const y = text.attr("y");
                    const dy = parseFloat(text.attr("dy"));
                    let tspan = text.text(null).append("tspan")
                        .attr("x", 0)
                        .attr("y", y)
                        .attr("dy", dy + "em");

                    while (word = words.pop()) {
                        line.push(word);
                        tspan.text(line.join(" "));
                        if (tspan.node().getComputedTextLength() > width) {
                            line.pop();
                            tspan.text(line.join(" "));
                            line = [word];
                            tspan = text.append("tspan")
                                .attr("x", 0)
                                .attr("y", y)
                                .attr("dy", ++lineNumber * lineHeight + dy + "em")
                                .text(word);
                        }
                    }
                });
            }

            // Improved zoom behavior
            currentZoom = d3.zoom()
                .scaleExtent([0.3, 2])
                .on("zoom", (event) => {
                    g.attr("transform", event.transform);
                });

            svg.call(currentZoom);

            // Set initial transform
            const bounds = g.node().getBBox();
            const fullWidth = bounds.width;
            const fullHeight = bounds.height;
            const scale = Math.min(
                width / fullWidth,
                height / fullHeight
            ) * 0.9;

            initialTransform = d3.zoomIdentity
                .translate(
                    width / 2 - bounds.x * scale - bounds.width * scale / 2,
                    height / 2 - bounds.y * scale - bounds.height * scale / 2
                )
                .scale(scale);

            svg.transition()
                .duration(1000)
                .call(currentZoom.transform, initialTransform);
        }

        // Zoom control functions
        function zoomIn() {
            if (!svg || !currentZoom) return;
            const transform = d3.zoomTransform(svg.node());
            svg.transition().duration(300).call(
                currentZoom.transform,
                d3.zoomIdentity.translate(transform.x, transform.y).scale(transform.k * 1.2)
            );
        }

        function zoomOut() {
            if (!svg || !currentZoom) return;
            const transform = d3.zoomTransform(svg.node());
            svg.transition().duration(300).call(
                currentZoom.transform,
                d3.zoomIdentity.translate(transform.x, transform.y).scale(transform.k * 0.8)
            );
        }

        function resetZoom() {
            if (!svg || !currentZoom || !initialTransform) return;
            svg.transition().duration(500).call(
                currentZoom.transform,
                initialTransform
            );
        }

        // Initialize when component is ready
        document.addEventListener('DOMContentLoaded', function() {
            const treeData = @json($treeData);
            console.log(treeData);
            if (treeData) {
                initBinaryTree(treeData);
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const treeData = @json($treeData);
            console.log(treeData);
            if (treeData) {
                initBinaryTree(treeData);
            }
        });
    </script>
    @endpush
</div>
