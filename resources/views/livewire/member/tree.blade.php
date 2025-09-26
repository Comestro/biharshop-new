<div class="space-y-6">
    <x-global.loader wire:loading message="Loading tree structure..." />
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with controls -->
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-medium text-gray-900">Binary Tree Structure</h2>
                <!-- Search Box -->
                <div class="mb-4 flex items-center space-x-2">
                    <input id="search-node" type="text" placeholder="Search by name..."
                        class="border px-3 py-2 rounded-lg w-64 focus:ring focus:border-blue-400" />
                    <button id="clear-search" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Clear
                    </button>
                </div>

                <!-- Zoom Controls -->
                <div class="flex items-center space-x-2">
                    <button id="zoom-in" class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <button id="zoom-out" class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>
                    <button id="zoom-reset" class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tree Container with border -->
            <div class="bg-white border rounded-lg">
                <div id="binary-tree-container" class="w-full h-[600px]" wire:ignore></div>
            </div>
        </div>

        <script src="https://d3js.org/d3.v7.min.js"></script>
        <script>
            let currentZoom;

            function initBinaryTree(data) {
                if (!data || data.length === 0) {
                    console.log('No tree data available');
                    return;
                }

                // Clear previous tree
                d3.select("#binary-tree-container").html("");

                const container = document.getElementById('binary-tree-container');
                const width = container.offsetWidth;
                const height = container.offsetHeight;
                const margin = { top: 50, right: 20, bottom: 50, left: 0 };

                // Create SVG container
                const svg = d3.select("#binary-tree-container")
                    .append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .attr("viewBox", [0, 0, width, height]);

                const g = svg.append("g");

                // Create hierarchy
                const stratify = d3.stratify()
                    .id(d => d.id)
                    .parentId(d => d.parentId);

                const root = stratify(data)
                    .sum(d => d.value || 1);

                // Create tree layout with proper spacing
                const treeLayout = d3.tree()
                    .size([width - margin.left - margin.right, height - margin.top - margin.bottom])
                    .nodeSize([150, 120]); // Increased vertical and horizontal spacing between nodes

                treeLayout(root);

                // Center the tree with more space
                const centerX = width / 2 - root.x;
                g.attr("transform", `translate(${centerX},${margin.top + 40})`); // Added extra top margin

                // Add links with straight lines
                const links = g.selectAll(".link")
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
                    .attr("stroke", "#e2e8f0")
                    .attr("stroke-width", 1.5);

                // Add nodes
                const nodes = g.selectAll(".node")
                    .data(root.descendants())
                    .join("g")
                    .attr("class", "node")
                    .attr("transform", d => `translate(${d.x},${d.y})`);

                // Node squares with different color for logged in user
                nodes.append("rect")
                    .attr("width", 120)
                    .attr("height", 60)
                    .attr("x", -60)
                    .attr("y", -30)
                    .attr("rx", 4)
                    .attr("ry", 4)
                    .attr("fill", d => {
                        if (d.data.status === 'empty') return '#f8fafc';
                        if (d.data.status === 'current') return '#ecfdf5'; // Light green background for current user
                        return d.data.status === 'verified' ? '#f0fdf4' : '#fef2f2';
                    })
                    .attr("stroke", d => {
                        if (d.data.status === 'empty') return '#e2e8f0';
                        if (d.data.status === 'current') return '#059669'; // Darker green border for current user
                        return d.data.status === 'verified' ? '#22c55e' : '#ef4444';
                    })
                    .attr("stroke-width", d => d.data.status === 'current' ? 3 : 2) // Thicker border for current user
                    .attr("class", "transition-colors duration-200");

                // Add labels
                const labels = nodes.append("g")
                    .attr("class", "label");

                labels.append("text")
                    .attr("dy", "-1.2em")
                    .attr("text-anchor", "middle")
                    .attr("class", "text-[10px] font-medium text-gray-500")
                    .text(d => d.data.token ? '#' + d.data.token : '');

                labels.append("text")
                    .attr("dy", "0.4em")
                    .attr("text-anchor", "middle")
                    .attr("class", "text-xs font-medium")
                    .text(d => d.data.name);

                // Add zoom behavior
                const zoom = d3.zoom()
                    .scaleExtent([0.3, 2])
                    .on("zoom", (event) => {
                        currentZoom = event.transform;
                        g.attr("transform", event.transform);
                    });

                svg.call(zoom);

                // Initial zoom to fit
                const transform = d3.zoomIdentity
                    .translate(width / 2, height / 6)
                    .scale(0.8);

                svg.call(zoom.transform, transform);
                currentZoom = transform;

                // Zoom Controls Event Handlers
                document.getElementById('zoom-in').onclick = () => {
                    svg.transition()
                        .duration(300)
                        .call(zoom.scaleBy, 1.2);
                };

                document.getElementById('zoom-out').onclick = () => {
                    svg.transition()
                        .duration(300)
                        .call(zoom.scaleBy, 0.8);
                };

                document.getElementById('zoom-reset').onclick = () => {
                    svg.transition()
                        .duration(300)
                        .call(zoom.transform, transform);
                };
            }
            // Search Function
            function searchNode(name) {
                const nodes = d3.selectAll(".node");

                nodes.select("rect")
                    .attr("stroke", d => {
                        if (!name) {
                            // Reset to original stroke
                            if (d.data.status === 'empty') return '#e2e8f0';
                            if (d.data.status === 'current') return '#059669';
                            return d.data.status === 'verified' ? '#22c55e' : '#ef4444';
                        }
                        // Highlight match
                        return d.data.name.toLowerCase().includes(name.toLowerCase())
                            ? '#2563eb'  // Blue border for match
                            : (d.data.status === 'empty' ? '#e2e8f0' :
                                d.data.status === 'current' ? '#059669' :
                                    d.data.status === 'verified' ? '#22c55e' : '#ef4444');
                    })
                    .attr("stroke-width", d => {
                        if (!name) return d.data.status === 'current' ? 3 : 2;
                        return d.data.name.toLowerCase().includes(name.toLowerCase()) ? 4 : 2;
                    });
            }

            // Event Listeners
            document.getElementById("search-node").addEventListener("input", (e) => {
                searchNode(e.target.value);
            });

            document.getElementById("clear-search").addEventListener("click", () => {
                document.getElementById("search-node").value = "";
                searchNode("");
            });

            // Initialize when mounted
            document.addEventListener('livewire:init', function () {
                const treeData = @json($treeData);
                console.log('Tree Data:', treeData);
                if (treeData && treeData.length > 0) {
                    initBinaryTree(treeData);
                }
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    const treeData = @json($treeData);
                    if (treeData && treeData.length > 0) {
                        initBinaryTree(treeData);
                    }
                }, 250);
            });
        </script>
    </div>
</div>