<div class="tree-node">
    <div class="node-content">
        <div class="font-medium">{{ $node['member']->name }}</div>
        <div class="text-sm text-gray-500">{{ $node['member']->token }}</div>
    </div>
    
    @if($node['left'] || $node['right'])
        <div class="node-children">
            <div class="flex justify-center gap-4">
                @if($node['left'])
                    @include('components.admin.tree-node', ['node' => $node['left']])
                @else
                    <div class="tree-node">
                        <div class="node-content bg-gray-50">
                            <span class="text-gray-400">Empty</span>
                        </div>
                    </div>
                @endif

                @if($node['right'])
                    @include('components.admin.tree-node', ['node' => $node['right']])
                @else
                    <div class="tree-node">
                        <div class="node-content bg-gray-50">
                            <span class="text-gray-400">Empty</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
