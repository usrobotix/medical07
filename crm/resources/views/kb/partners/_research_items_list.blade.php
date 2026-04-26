{{--
    Partial: render a research profile list field (doctors, prices, reviews, sources, etc.)

    Variables:
      $items  – array of items (plain strings, JSON-encoded strings, or associative arrays)
      $title  – section label (string)
--}}
@if(!empty($items))
    <div class="mb-4">
        <p class="text-ys-xs text-dc-secondary mb-1">{{ $title }}</p>
        <ul class="text-ys-s space-y-1">
            @foreach($items as $item)
                @php
                    // Decode item: may be a plain string, a JSON-encoded string, or an array.
                    if (is_string($item)) {
                        $decoded = json_decode($item, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $item = $decoded;
                        }
                    }
                    if (is_array($item)) {
                        $iUrl  = $item['url'] ?? null;
                        $iDesc = $item['описание'] ?? ($item['description'] ?? null);
                        $iDate = $item['дата_проверки'] ?? ($item['date'] ?? null);
                        $iName = $item['name'] ?? ($item['название'] ?? null);
                        $iText = null;
                    } else {
                        $iUrl  = null;
                        $iDesc = null;
                        $iDate = null;
                        $iName = null;
                        $iText = (string) $item;
                    }
                @endphp
                <li class="text-dc">
                    @if($iUrl && str_starts_with($iUrl, 'http'))
                        <a href="{{ $iUrl }}" target="_blank" rel="noopener noreferrer" class="text-dc-primary hover:underline dc-transition break-all">{{ $iName ?? $iUrl }}</a>
                    @elseif($iName)
                        <span>{{ $iName }}</span>
                    @elseif($iText)
                        @if(str_starts_with($iText, 'http'))
                            <a href="{{ $iText }}" target="_blank" rel="noopener noreferrer" class="text-dc-primary hover:underline dc-transition break-all">{{ $iText }}</a>
                        @else
                            <span>{{ $iText }}</span>
                        @endif
                    @else
                        <span>—</span>
                    @endif
                    @if($iDesc)
                        <span class="text-dc-secondary"> — {{ $iDesc }}</span>
                    @endif
                    @if($iDate)
                        <span class="text-dc-secondary text-ys-xs"> ({{ $iDate }})</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
