<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">My Network Structure</h2>
            
            <div class="flex justify-center">
                <div id="member-tree-container" class="w-full h-[600px]" wire:ignore></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>
        function initBinaryTree(data) {
            // Clear previous tree
            d3.select("#member-tree-container").html("");

            const width = document.getElementById('member-tree-container').offsetWidth;
            const height = 600;
            const margin = {top: 50, right: 20, bottom: 50, left: 20};

            // Create the SVG container
            const svg = d3.select("#member-tree-container")
                .append("svg")
                .attr("width", width)
                .attr("height", height);

            const g = svg.append("g");

            // Create hierarchy
            const stratify = d3.stratify()
                .id(d => d.id)
                .parentId(d => d.parentId);

            const root = stratify(data)
                .sum(d => d.value || 1);

            const treeLayout = d3.tree()
                .size([width - margin.left - margin.right, height - margin.top - margin.bottom])
                .nodeSize([80, 100]);

            treeLayout(root);

            // Center the tree
            const centerX = width / 2 - root.x;
            g.attr("transform", `translate(${centerX},${margin.top})`);

            // Add links
            const links = g.selectAll(".link")
                .data(root.links())
                .join("path")
                .attr("class", "link")
                .attr("d", d3.linkVertical()
                    .x(d => d.x)
                    .y(d => d.y))
                .attr("fill", "none")
                .attr("stroke", "#e2e8f0")
                .attr("stroke-width", 2);

            // Add nodes
            const nodes = g.selectAll(".node")
                .data(root.descendants())
                .join("g")
                .attr("class", "node")
                .attr("transform", d => `translate(${d.x},${d.y})`);

            // Node circles
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
                .attr("stroke-width", 3);

            // Node labels
            const labels = nodes.append("g")
                .attr("class", "label");

            // Member ID
            labels.append("text")
                .attr("dy", "-1.2em")
                .attr("text-anchor", "middle")
                .attr("class", "text-[10px] font-medium text-gray-500")
                .text(d => d.data.token ? '#' + d.data.token : '');

            // Member name
            labels.append("text")
                .attr("dy", "0.4em")
                .attr("text-anchor", "middle")
                .attr("class", "text-xs font-medium")
                .text(d => d.data.name);

            // Add zoom behavior
            const zoom = d3.zoom()
                .scaleExtent([0.3, 2])
                .on("zoom", (event) => {
                    g.attr("transform", event.transform);
                });

            svg.call(zoom);
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
