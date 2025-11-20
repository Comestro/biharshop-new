<div class="min-h-screen bg-gray-50 flex flex-col" x-data="binaryTreeModal()"  x-init="init()">
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
                <button id="global-search"
                    class="text-sm bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg transition flex-shrink-0">Search All</button>
            </div>

            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <button id="go-back-root"
                    class="text-sm px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Back</button>
                <button id="reset-tree-root"
                    class="text-sm px-3 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700">Reset Tree</button>
            </div>

            <div id="nav-breadcrumb" class="flex items-center flex-wrap gap-1 text-sm text-gray-600 mt-2 sm:mt-0"></div>

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
        <div class="bg-white rounded-b-2xl shadow-sm overflow-hidden">
            <div id="binary-tree-container"
                class="w-full h-[100vh]  bg-[radial-gradient(circle_at_center,_#f8fafc_1px,_transparent_1px)] [background-size:24px_24px] transition-all"
                wire:ignore></div>
        </div>
    </main>

<!-- Add-node modal removed -->
<!--
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" x-show="open" x-cloak>
    ...
</div>
-->
</div>

<!-- D3 Script -->
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
(function(){
    try {
        const s = document.currentScript;
        if (!s) return;
        if (window.Livewire && !window.AdminBinaryTreeWire) {
            const root = s.closest('[wire\\:id]');
            if (!root) return;
            const id = root.getAttribute('wire:id');
            const comp = Livewire.find(id);
            if (comp && !window.AdminBinaryTreeWire) window.AdminBinaryTreeWire = comp;
        }
    } catch(e) {}
})();
</script>
<script>
// Add-node modal logic removed
function binaryTreeModal() {
    return {
        open: false,
        parentId: null,
        position: null,
        init() {
            // disabled
        },
        close() {
            this.open = false;
        }
    }
}
</script>
<script>
    let currentZoom;
    let navigationStack = [];
    let currentRootId = null;
    let initialRootId = null;
    let pendingGlobalSearchQuery = '';


    function linkRounded(d) {
        const x1 = d.source.x;
        const y1 = d.source.y;
        const x2 = d.target.x;
        const y2 = d.target.y;

        const radius = 16; // curve roundness
        const midY = (y1 + y2) / 2; // horizontal rail halfway down
        const sign = x2 > x1 ? 1 : -1; // direction of curve

        // outward-curving elbow link
        return `
    M${x1},${y1}
    V${midY}
    Q${x1},${midY} ${x1 + sign * radius},${midY}
    H${x2 - sign * radius}
    Q${x2},${midY} ${x2},${midY + radius}
    V${y2}
  `;
    }


    function initBinaryTree(data) {
        if (!data || data.length === 0) return;
        d3.select("#binary-tree-container").html("");

        const container = document.getElementById('binary-tree-container');
        if (!container) {
            // DOM not ready or container missing; safely bail
            return;
        }
        const width = container.offsetWidth;
        const height = container.offsetHeight;
        const margin = {
            top: 50,
            right: 40,
            bottom: 50,
            left: 40
        };

        const svg = d3.select("#binary-tree-container")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .attr("viewBox", [-70, 100, width, height]);
            

        const g = svg.append("g");
        const defs = svg.append("defs");

        const stratify = d3.stratify()
            .id(d => d.id)
            .parentId(d => d.parentId);

        const root = stratify(data).sum(d => d.value || 1);
        currentRootId = root.id;
        if (!initialRootId) initialRootId = root.id;

        const treeLayout = d3.tree()
            .size([width - margin.left - margin.right, height - margin.top - margin.bottom])
            .nodeSize([70, 140]);

        treeLayout(root);

        const centerX = width / 2 - root.x;
        g.attr("transform", `translate(${centerX},${margin.top + 0})`);

        // Links
        g.selectAll(".link")
            .data(root.links())
            .join("path")
            .attr("class", "link")
            .attr('d', linkRounded)
            .attr("fill", "none")
            .attr("stroke", "#ddd")
            .attr("stroke-width", 2);

        // Nodes
        const nodes = g.selectAll(".node")
            .data(root.descendants())
            .join("g")
            .attr("class", "node group")
            .attr("transform", d => `translate(${d.x},${d.y})`)
            .style("cursor", d => d.data.status !== 'empty' ? "pointer" : "default")
            .on("click", function(event, d) {
                const t = document.getElementById('binary-tree-tooltip');
                if (t) t.style.display = 'none';
                const rawId = d && d.data ? d.data.id : null;
                const idStr = rawId !== null && rawId !== undefined ? String(rawId) : '';
                if (!idStr) return;

                // Add-node disabled: clicking on empty nodes does nothing
                // if (idStr.startsWith('empty-')) { return; }
                navigationStack.push(currentRootId);
                Livewire.dispatch('binaryTreeChangeRootRequest', { id: rawId });
                console.log('Dispatched binaryTreeChangeRootRequest with id:', rawId);
                updateBreadcrumbUI();
            });
        // For avatar clipping helper
        function safeId(id) {
            return String(id).replace(/[^a-zA-Z0-9_-]/g, '_');
        }

        // Create per-node clipPaths for avatars (rounded rect)
        nodes.each(function(d) {
            const id = safeId(d.data.id);
            const w = d.data.status === 'empty' ? 40 : 60;
            const h = w;
            const x = -w / 2;
            const y = -h / 2;
            if (!defs.select(`#clip-${id}`).size()) {
                const cp = defs.append('clipPath').attr('id', `clip-${id}`);
                cp.append('rect')
                    .attr('x', x)
                    .attr('y', y)
                    .attr('width', w)
                    .attr('height', h)
                    .attr('rx', 10)
                    .attr('ry', 10);
            }
        });

        // Rounded square border/background (node)
        nodes.append("rect")
            .attr("x", d => d.data.status === 'empty' ? -20 : -30)
            .attr("y", d => d.data.status === 'empty' ? -20 : -30)
            .attr("width", d => d.data.status === 'empty' ? 40 : 60)
            .attr("height", d => d.data.status === 'empty' ? 40 : 60)
            .attr('rx', 50)
            .attr('ry', 50)
            .attr("fill", d => {
                if (d.data.status === 'empty') return '#f9fafb';
                if (d.data.status === 'current') return '#ecfdf5';
                return d.data.status === 'verified' ? '#f0fdf4' : '#fef2f2';
            })
            .attr("stroke", d => {
                if (d.data.status === 'empty') return '#e2e8f0';
                if (d.data.status === 'current') return '#059669';
                return d.data.status === 'verified' ? '#22c55e' : '#e2e8f0'; // Use gray for non-verified
            })
            .attr("stroke-width", d => d.data.status === 'current' ? 3 : 2)
            .attr("stroke-dasharray", d => d.data.status === 'empty' ? "5,5" : "none")
            .attr("filter", "drop-shadow(0px 2px 2px rgba(0,0,0,0.05))")
            .on("mouseover", function() {
                if (d3.select(this).datum().data.status !== 'empty') {
                    d3.select(this).transition().duration(200).attr("stroke-width", 3).attr("transform",
                        "scale(1.03)");
                }
            })
            .on("mouseout", function(d) {
                const datum = d3.select(this).datum();
                d3.select(this).transition().duration(200).attr("stroke-width", datum.data.status === 'current' ?
                    3 : 2).attr("transform", "scale(1)");
            });

            console.log(data)

        // Avatar image (if provided)
        nodes.append('image')
            .attr('x', d => d.data.status === 'empty' ? -20 : -30)
            .attr('y', d => d.data.status === 'empty' ? -20 : -30)
            .attr('width', d => d.data.status === 'empty' ? 40 : 60)
            .attr('height', d => d.data.status === 'empty' ? 40 : 60)
            .attr('preserveAspectRatio', 'xMidYMid slice')
            .attr('href', d => d.data.avatar ? d.data.avatar : '')
            .attr('clip-path', d => `url(#clip-${safeId(d.data.id)})`)
            .style('display', d => d.data.avatar ? null : 'none');

        // Labels
        const labels = nodes.append("g").attr("class", "label text-center");
        // Initial inside circle for non-empty
        labels.filter(d => d.data.status !== 'empty').append("text")
            .attr("dy", "0.35em")
            .attr("text-anchor", "middle")
            .attr("class", "text-[16px] font-bold text-gray-700 fill-current")
            .text(d => (d.data.initials || (d.data.name ? d.data.name.charAt(0) : '')).toUpperCase());

        // Plus icon centered for empty nodes
        labels.filter(d => d.data.status === 'empty').append('text')
            .attr('dy', '0.35em')
            .attr('text-anchor', 'middle')
            .attr('class', 'text-[18px] font-bold')
            .attr('fill', '#2563eb')
            .text('+');

        // Name badge on node (non-empty): pill below the square
        const badges = nodes.append('g').attr('class', 'name-badge');
        const nonEmptyBadges = badges.filter(d => d.data.status !== 'empty');
        nonEmptyBadges.append('rect')
            .attr('x', -52)
            .attr('y', 40)
            .attr('width',100)
            .attr('height', 18)
            .attr('rx', 9)
            .attr('ry', 9)
            .attr('fill', '#2563eb')
            .attr('opacity', 0.9);
        nonEmptyBadges.append('text')
            .attr('x', 0)
            .attr('y', 53)
            .attr('text-anchor', 'middle')
            .attr('class', 'text-[11px] font-semibold')
            .attr('fill', '#ffffff')
            .text(d => (d.data.name || '').length > 18 ? (d.data.name || '').slice(0, 17) + '…' : (d.data.name || ''));

        // Hint badge for empty nodes
        const emptyBadges = badges.filter(d => d.data.status === 'empty');
        emptyBadges.append('rect')
            .attr('x', -28)
            .attr('y', 40)
            .attr('width', 56)
            .attr('height', 18)
            .attr('rx', 9)
            .attr('ry', 9)
            .attr('fill', '#e5f2ff')
            .attr('stroke', '#3b82f6')
            .attr('opacity', 0.9);
        emptyBadges.append('text')
            .attr('x', 0)
            .attr('y', 53)
            .attr('text-anchor', 'middle')
            .attr('class', 'text-[11px] font-semibold')
            .attr('fill', '#1d4ed8')
            .text('Add');

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
        document.getElementById('zoom-reset').onclick = () => svg.transition().duration(300).call(zoom.transform,
            transform);

        // Tooltip element (created once)
        let tooltipEl = document.getElementById('binary-tree-tooltip');
        if (!tooltipEl) {
            tooltipEl = document.createElement('div');
            tooltipEl.id = 'binary-tree-tooltip';
            tooltipEl.style.position = 'fixed';
            tooltipEl.style.zIndex = '9999';
            tooltipEl.style.pointerEvents = 'none';
            tooltipEl.style.background = '#ffffff';
            tooltipEl.style.border = '1px solid #e5e7eb';
            tooltipEl.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
            tooltipEl.style.borderRadius = '8px';
            tooltipEl.style.padding = '8px 10px';
            tooltipEl.style.fontSize = '12px';
            tooltipEl.style.color = '#111827';
            tooltipEl.style.display = 'none';
            document.body.appendChild(tooltipEl);
        }

        // Tooltip handlers on node groups
        nodes
            .on('mouseover', function(event, d) {
                if (d?.data?.status === 'empty') return;
                const memberid = d?.data?.membership_id ?? '—';
                const name = d?.data?.name || '—';
                const status = d?.data?.status || '—';
                const id = d?.data?.id ?? '—';
                tooltipEl.innerHTML = `
                    <div style="display:flex;flex-direction:column;gap:4px;min-width:200px;max-width:280px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="display:inline-block;padding:2px 8px;background:#2563eb;color:#fff;border-radius:999px;font-weight:600;font-size:11px;">${name}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;gap:12px;">
                            <div><span style="color:#6b7280;">MemberID:</span> <span style="color:#111827;">${memberid}</span></div>
                            <div><span style="color:#6b7280;">Status:</span> <span style="color:#111827;">${status}</span></div>
                        </div>
                    </div>`;
                tooltipEl.style.display = 'block';
            })
            .on('mousemove', function(event) {
                const offset = 12;
                tooltipEl.style.left = (event.pageX + offset) + 'px';
                tooltipEl.style.top = (event.pageY + offset) + 'px';
            })
            .on('mouseout', function() {
                tooltipEl.style.display = 'none';
            });

        updateBreadcrumbUI();
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

    function updateBreadcrumbUI() {
        const el = document.getElementById('nav-breadcrumb');
        if (!el) return;
        el.innerHTML = '';
        const items = [...navigationStack, currentRootId].filter(Boolean);
        items.forEach((id, idx) => {
            const b = document.createElement('button');
            b.className = 'px-2 py-1 rounded hover:bg-gray-100';
            b.textContent = idx === 0 ? 'Root' : `#${id}`;
            b.onclick = () => {
                Livewire.dispatch('binaryTreeChangeRootRequest', { id });
            };
            el.appendChild(b);
            if (idx < items.length - 1) {
                const sep = document.createElement('span');
                sep.textContent = '›';
                sep.className = 'mx-1 text-gray-400';
                el.appendChild(sep);
            }
        });
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

    document.getElementById('search-node').addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            const q = e.target.value.trim();
            if (q) {
                pendingGlobalSearchQuery = q;
                Livewire.dispatch('binaryTreeSearch', { query: q });
            }
        }
    });

    document.getElementById('global-search').addEventListener('click', () => {
        const q = document.getElementById('search-node').value.trim();
        if (q) {
            pendingGlobalSearchQuery = q;
            Livewire.dispatch('binaryTreeSearch', { query: q });
        }
    });

    document.addEventListener('livewire:init', function() {
        try { window.AdminBinaryTreeWire = @this; } catch (e) {}
        const treeData = @json($treeData);
        if (treeData && treeData.length > 0) initBinaryTree(treeData);
        // Listen for Livewire dispatched browser events or Livewire JS events
        try {
            if (window.Livewire && typeof Livewire.on === 'function') {
                // Listen for updated tree data from Livewire component
                Livewire.on('binaryTreeDataUpdated', function(treeData) {
                    initBinaryTree(treeData);
                });
            }
        } catch (err) {
            // ignore
        }

        window.addEventListener('binaryTreeDataUpdated', function(e) {
            if (e && e.detail && e.detail.treeData) {
                initBinaryTree(e.detail.treeData);
                if (pendingGlobalSearchQuery) {
                    searchNode(pendingGlobalSearchQuery);
                }
            }
        });

        // Add-node event disabled
        // window.addEventListener('admin-binary-tree:open-empty-slot', function(e) { /* disabled */ });
    });

        window.addEventListener('resize', () => {
            clearTimeout(window.resizeTimer);
            window.resizeTimer = setTimeout(() => {
                const treeData = @json($treeData);
                if (treeData && treeData.length > 0) initBinaryTree(treeData);
            }, 250);
        });

        document.addEventListener('click', function() {
            const t = document.getElementById('binary-tree-tooltip');
            if (t) t.style.display = 'none';
        });

        document.getElementById('go-back-root').addEventListener('click', function() {
            if (navigationStack.length > 0) {
                const prev = navigationStack.pop();
                if (prev) Livewire.dispatch('binaryTreeChangeRootRequest', { id: prev });
            }
            updateBreadcrumbUI();
        });

        document.getElementById('reset-tree-root').addEventListener('click', function() {
            if (initialRootId) {
                navigationStack = [];
                Livewire.dispatch('binaryTreeChangeRootRequest', { id: initialRootId });
                pendingGlobalSearchQuery = '';
            }
            updateBreadcrumbUI();
        });
    </script>
