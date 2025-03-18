<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Binary Tree Structure</h2>
            
            <div class="flex justify-center">
                <div id="binary-tree-container" class="w-full h-[600px]" wire:ignore></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>
        function initBinaryTree(data) {
            if (!data) return;
            
            // Clear previous tree
            d3.select("#binary-tree-container").html("");

            const width = document.getElementById('binary-tree-container').offsetWidth;
            const height = 600;
            const margin = {top: 50, right: 20, bottom: 50, left: 20};

            // Create the SVG container
            const svg = d3.select("#binary-tree-container")
                .append("svg")
                .attr("width", width)
                .attr("height", height);

            const g = svg.append("g");

            // Hierarchy
            const root = d3.hierarchy(data);

            // Tree layout with proper spacing
            const treeLayout = d3.tree()
                .size([width - margin.left - margin.right, height - margin.top - margin.bottom])
                .nodeSize([80, 100]); // Set consistent node spacing

            // Apply layout
            treeLayout(root);

            // Center the tree
            const centerX = width / 2 - root.x;
            g.attr("transform", `translate(${centerX},${margin.top})`);

            // Links with curved paths
            const links = g.selectAll(".link")
                .data(root.links())
                .join("path")
                .attr("class", "link")
                .attr("d", d3.linkVertical()
                    .x(d => d.x)
                    .y(d => d.y))
                .attr("fill", "none")
                .attr("stroke", "#e2e8f0")
                .attr("stroke-width", 2)
                .attr("marker-end", "url(#arrowhead)"); // Add arrow markers

            // Add arrow marker definition
            svg.append("defs").append("marker")
                .attr("id", "arrowhead")
                .attr("viewBox", "0 -5 10 10")
                .attr("refX", 38) // Position of the arrow
                .attr("refY", 0)
                .attr("markerWidth", 8)
                .attr("markerHeight", 8)
                .attr("orient", "auto")
                .append("path")
                .attr("d", "M0,-5L10,0L0,5")
                .attr("fill", "#e2e8f0");

            // Nodes
            const nodes = g.selectAll(".node")
                .data(root.descendants())
                .join("g")
                .attr("class", "node")
                .attr("transform", d => `translate(${d.x},${d.y})`);

            // Node circles with different styles based on status
            nodes.append("circle")
                .attr("r", 30)
                .attr("fill", d => {
                    if (d.data.status === 'empty') return '#f3f4f6';
                    return d.data.status === 'verified' ? '#fff' : '#fee2e2';
                })
                .attr("stroke", d => {
                    if (d.data.status === 'empty') return '#e2e8f0';
                    return d.data.status === 'verified' ? '#10b981' : '#ef4444';
                })
                .attr("stroke-width", 3)
                .attr("class", "transition-colors duration-200 hover:stroke-indigo-500");

            // Node labels
            const labels = nodes.append("g")
                .attr("class", "label");

            // Name text
            labels.append("text")
                .attr("dy", "-0.5em")
                .attr("text-anchor", "middle")
                .attr("class", "text-sm font-medium")
                .text(d => d.data.name)
                .call(wrap, 80); // Wrap long text

            // ID text
            labels.append("text")
                .attr("dy", "1.5em")
                .attr("text-anchor", "middle")
                .attr("class", "text-xs text-gray-500")
                .text(d => d.data.token);

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

            // Add zoom behavior
            const zoom = d3.zoom()
                .scaleExtent([0.3, 2])
                .on("zoom", (event) => {
                    g.attr("transform", event.transform);
                });

            svg.call(zoom);
            
            // Initial zoom to fit
            const bounds = g.node().getBBox();
            const fullWidth = bounds.width;
            const fullHeight = bounds.height;
            const scale = Math.min(
                width / fullWidth,
                height / fullHeight
            ) * 0.9;
            
            const initialTransform = d3.zoomIdentity
                .translate(
                    width / 2 - bounds.x * scale - bounds.width * scale / 2,
                    height / 2 - bounds.y * scale - bounds.height * scale / 2
                )
                .scale(scale);

            svg.call(zoom.transform, initialTransform);
        }

        // Initialize when component is ready
        document.addEventListener('DOMContentLoaded', function() {
            const treeData = @json($treeData);
            if (treeData) {
                initBinaryTree(treeData);
            }
        });
    </script>
    @endpush
</div>
