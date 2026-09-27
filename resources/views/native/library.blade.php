<native:scroll-view class="w-full h-full bg-amber-50">
    <native:column class="w-full gap-5 px-5 pt-8 pb-12 safe-area">
        <native:row class="w-full items-center justify-between">
            <native:column class="gap-1">
                <native:text class="text-sm font-bold text-orange-700">LEAFNOTE / YOUR SHELF</native:text>
                <native:text class="text-4xl font-extrabold text-slate-900">Books worth remembering.</native:text>
            </native:column>
            <native:icon name="book" :size="32" class="text-orange-700" />
        </native:row>

        <native:column class="w-full gap-2 rounded-3xl bg-slate-900 p-5">
            <native:text class="text-sm font-semibold text-amber-300">A READING JOURNAL</native:text>
            <native:text class="text-2xl font-bold text-white">{{ $bookCount }} {{ $bookCount === 1 ? 'book' : 'books' }} on your shelf</native:text>
            <native:text class="text-sm text-slate-300">Keep the ideas that stayed with you.</native:text>
        </native:column>

        <native:text class="text-xl font-bold text-slate-900">Your shelf</native:text>

        @forelse ($books as $book)
            <native:column class="w-full gap-2 rounded-2xl bg-white p-5">
                <native:row class="w-full items-center justify-between">
                    <native:text class="flex-1 text-lg font-bold text-slate-900">{{ $book->title }}</native:text>
                    <native:pressable ref="remove-{{ $book->id }}" @press="remove({{ $book->id }})" a11y-label="Delete review for {{ $book->title }}" class="w-11 h-11 items-center justify-center">
                        <native:icon name="delete" :size="18" a11y-label="Delete review for {{ $book->title }}" class="text-slate-500" />
                    </native:pressable>
                </native:row>
                <native:text class="text-sm text-slate-600">by {{ $book->author }}</native:text>
                <native:text class="text-lg text-orange-600">{{ str_repeat('★', $book->rating) }}</native:text>
                <native:text class="text-base text-slate-700">{{ $book->review }}</native:text>
            </native:column>
        @empty
            <native:column class="w-full items-center gap-2 rounded-2xl bg-white p-6">
                <native:icon name="book" :size="30" class="text-orange-600" />
                <native:text class="text-base font-semibold text-slate-900">Your next favourite goes here.</native:text>
                <native:text class="text-sm text-slate-600">Add a book and give it a rating below.</native:text>
            </native:column>
        @endforelse

        <native:column class="w-full gap-3 rounded-2xl bg-white p-4">
            <native:text class="text-xl font-bold text-slate-900">Add a book</native:text>
            <native:outlined-text-input native:key="title-{{ $formVersion }}" ref="title-input" label="Book title" native:model.debounce.750ms="title" max-length="160" />
            <native:outlined-text-input native:key="author-{{ $formVersion }}" ref="author-input" label="Author" native:model.debounce.750ms="author" max-length="120" />
            <native:outlined-text-input native:key="review-{{ $formVersion }}" ref="review-input" label="What stayed with you?" native:model.debounce.750ms="review" max-length="2000" multiline :min-lines="2" :max-lines="5" />

            <native:text class="text-sm font-semibold text-slate-600">YOUR RATING</native:text>
            <native:row class="w-full gap-2">
                @for ($star = 1; $star <= 5; $star++)
                    <native:pressable ref="rating-{{ $star }}" @press="setRating({{ $star }})" a11y-label="Set rating to {{ $star }} out of 5 stars{{ $rating === $star ? ', current rating' : '' }}" class="w-11 h-11 items-center justify-center rounded-xl bg-amber-100">
                        <native:icon name="{{ $star <= $rating ? 'star.fill' : 'star' }}" :size="24" a11y-label="Set rating to {{ $star }} out of 5 stars{{ $rating === $star ? ', current rating' : '' }}" class="{{ $star <= $rating ? 'text-orange-600' : 'text-slate-400' }}" />
                    </native:pressable>
                @endfor
            </native:row>

            @if ($error !== '')
                <native:text class="text-sm font-semibold text-red-700">{{ $error }}</native:text>
            @endif

            <native:pressable ref="save-book" @press="add" class="w-full items-center rounded-xl bg-orange-600 p-3">
                <native:text class="text-base font-bold text-white">Save to shelf</native:text>
            </native:pressable>
        </native:column>
    </native:column>
</native:scroll-view>
