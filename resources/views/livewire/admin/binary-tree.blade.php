<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header
        class="bg-white shadow-sm p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-t-2xl">
        <div class="">
            <h1 class="text-2xl font-semibold text-gray-800 tracking-tight">Binary Tree Structure</h1>
            <p class="text-sm text-gray-500">Visual representation of your downline structure</p>
        </div>

        <!-- Control Panel -->
        <div
            class="flex items-center gap-3 bg-white/70 backdrop-blur-sm shadow-sm border border-gray-200 rounded-xl p-1 flex-wrap justify-end">
            <!-- Search -->
            <div class="flex items-center gap-2 flex-1 min-w-[200px]">
                <input id="search-node" type="text" placeholder="Search by name..."
                    class="border-gray-300 text-sm px-3 py-2 rounded-lg outline-none w-full border-2" />
                <button id="clear-search"
                    class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition flex-shrink-0">Clear</button>
            </div>

            <!-- Zoom Controls -->
            <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                <button id="zoom-in"
                    class="p-2 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Zoom In">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
                <button id="zoom-out"
                    class="p-2 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Zoom Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>
                <button id="zoom-reset"
                    class="p-2 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Reset Zoom">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>


    <!-- Tree Container -->
    <main class="flex-1 ">
        <div class="bg-white border rounded-b-2xl shadow-sm overflow-hidden">
            <div id="binary-tree-container"
                class="w-full h-[100vh] bg-[radial-gradient(circle_at_center,_#f8fafc_1px,_transparent_1px)] [background-size:24px_24px] transition-all"
                wire:ignore></div>
        </div>
    </main>
</div>

<!-- D3 Script -->
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    let currentZoom;

    function initBinaryTree(data) {
        if (!data || data.length === 0) return;
        d3.select("#binary-tree-container").html("");

        const container = document.getElementById('binary-tree-container');
        const width = container.offsetWidth;
        const height = container.offsetHeight;
        const margin = { top: 50, right: 40, bottom: 50, left: 40 };

        const svg = d3.select("#binary-tree-container")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .attr("viewBox", [0, 0, width, height]);

        const g = svg.append("g");

        const stratify = d3.stratify()
            .id(d => d.id)
            .parentId(d => d.parentId);

        const root = stratify(data).sum(d => d.value || 1);

        const treeLayout = d3.tree()
            .size([width - margin.left - margin.right, height - margin.top - margin.bottom])
            .nodeSize([160, 120]);

        treeLayout(root);

        const centerX = width / 2 - root.x;
        g.attr("transform", `translate(${centerX},${margin.top + 40})`);

        // Links
        g.selectAll(".link")
            .data(root.links())
            .join("path")
            .attr("class", "link")
            .attr("d", d => `
                M${d.source.x},${d.source.y}
                L${d.source.x},${(d.source.y + d.target.y) / 2}
                L${d.target.x},${(d.source.y + d.target.y) / 2}
                L${d.target.x},${d.target.y}
            `)
            .attr("fill", "none")
            .attr("stroke", "#e5e7eb")
            .attr("stroke-width", 1.4);

        // Nodes
        const nodes = g.selectAll(".node")
            .data(root.descendants())
            .join("g")
            .attr("class", "node group")
            .attr("transform", d => `translate(${d.x},${d.y})`);

        nodes.append("rect")
            .attr("width", 130)
            .attr("height", 66)
            .attr("x", -65)
            .attr("y", -33)
            .attr("rx", 10)
            .attr("ry", 10)
            .attr("fill", d => {
                if (d.data.status === 'empty') return '#f9fafb';
                if (d.data.status === 'current') return '#ecfdf5';
                return d.data.status === 'verified' ? '#f0fdf4' : '#fef2f2';
            })
            .attr("stroke", d => {
                if (d.data.status === 'empty') return '#e2e8f0';
                if (d.data.status === 'current') return '#059669';
                return d.data.status === 'verified' ? '#22c55e' : '#ef4444';
            })
            .attr("stroke-width", d => d.data.status === 'current' ? 3 : 2)
            .attr("filter", "drop-shadow(0px 2px 2px rgba(0,0,0,0.05))")
            .on("mouseover", function () {
                d3.select(this).transition().duration(200).attr("stroke-width", 3).attr("transform", "scale(1.03)");
            })
            .on("mouseout", function (d) {
                d3.select(this).transition().duration(200).attr("stroke-width", d.data?.status === 'current' ? 3 : 2).attr("transform", "scale(1)");
            });

        // Labels
        const labels = nodes.append("g").attr("class", "label text-center");
        labels.append("text")
            .attr("dy", "-0.8em")
            .attr("text-anchor", "middle")
            .attr("class", "text-[11px] font-medium text-gray-500")
            .text(d => d.data.token ? '#' + d.data.token : '');
        labels.append("text")
            .attr("dy", "0.5em")
            .attr("text-anchor", "middle")
            .attr("class", "text-[13px] font-semibold text-gray-700")
            .text(d => d.data.name);

        // Zoom behavior
        const zoom = d3.zoom()
            .scaleExtent([0.01, 3]) // <-- change minimum 0.1 (more zoom out) and max 3 (more zoom in)
            .on("zoom", (event) => {
                currentZoom = event.transform;
                g.attr("transform", event.transform);
            });


        svg.call(zoom);

        const transform = d3.zoomIdentity.translate(width / 2, height / 6).scale(0.8);
        svg.call(zoom.transform, transform);
        currentZoom = transform;

        // Zoom buttons inside container
        document.getElementById('zoom-in').onclick = () => svg.transition().duration(300).call(zoom.scaleBy, 1.2);
        document.getElementById('zoom-out').onclick = () => svg.transition().duration(300).call(zoom.scaleBy, 0.8);
        document.getElementById('zoom-reset').onclick = () => svg.transition().duration(300).call(zoom.transform, transform);
    }

    // Search & focus node
    // Search & focus node without changing current zoom scale
    function searchNode(name) {
        const nodes = d3.selectAll(".node");
        let matchedNode = null;

        nodes.select("rect")
            .transition().duration(200)
            .attr("stroke", d => {
                if (!name) {
                    if (d.data.status === 'empty') return '#e2e8f0';
                    if (d.data.status === 'current') return '#059669';
                    return d.data.status === 'verified' ? '#22c55e' : '#ef4444';
                }
                if (d.data.name.toLowerCase().includes(name.toLowerCase())) {
                    matchedNode = d;
                    return '#2563eb';
                }
                return d.data.status === 'empty' ? '#e2e8f0' :
                    d.data.status === 'current' ? '#059669' :
                        d.data.status === 'verified' ? '#22c55e' : '#ef4444';
            })
            .attr("stroke-width", d => {
                if (!name) return d.data.status === 'current' ? 3 : 2;
                return d.data.name.toLowerCase().includes(name.toLowerCase()) ? 4 : 2;
            });

        // Only pan to node, do not change zoom scale
        if (matchedNode) {
            const svg = d3.select("#binary-tree-container svg");
            const g = svg.select("g");
            const container = document.getElementById('binary-tree-container');
            const width = container.offsetWidth;
            const height = container.offsetHeight;
            const scale = currentZoom ? currentZoom.k : 1;

            const translateX = width / 2 - matchedNode.x * scale;
            const translateY = height / 2 - matchedNode.y * scale;

            g.transition()
                .duration(700)
                .attr("transform", `translate(${translateX},${translateY}) scale(${scale})`);

            // Update currentZoom translate only, keep scale intact
            currentZoom = d3.zoomIdentity.translate(translateX, translateY).scale(scale);
        }
    }

    // Clear search restores original position without zooming out
    document.getElementById("clear-search").addEventListener("click", () => {
        document.getElementById("search-node").value = "";
        searchNode(""); // resets strokes
        if (currentZoom) {
            const svg = d3.select("#binary-tree-container svg");
            const g = svg.select("g");
            g.transition()
                .duration(700)
                .attr("transform", `translate(${currentZoom.x},${currentZoom.y}) scale(${currentZoom.k})`);
        }
    });


    // Event listeners
    document.getElementById("search-node").addEventListener("input", (e) => searchNode(e.target.value));
    document.getElementById("clear-search").addEventListener("click", () => {
        document.getElementById("search-node").value = "";
        searchNode("");
    });

    document.addEventListener('livewire:init', function () {
        const treeData = @json($treeData);
        if (treeData && treeData.length > 0) initBinaryTree(treeData);
    });

    window.addEventListener('resize', () => {
        clearTimeout(window.resizeTimer);
        window.resizeTimer = setTimeout(() => {
            const treeData = @json($treeData);
            if (treeData && treeData.length > 0) initBinaryTree(treeData);
        }, 250);
    });
</script>