<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-6">
            <div>
                <h1 class="font-bold text-4xl">Let's Find You A Great Job</h1>
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('danger') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <x-forms.form action="/search" class="mt-6" method="GET"> 
                    <x-forms.input :label="false" name="q" placeholder="Web developer..." />
                </x-forms.form>
            </div>
        </section>

        <section class="pt-10">
            <x-section-heading>Feature Jobs</x-section-heading>
            <div class="grid lg:grid-cols-3 gap-8 mt-6">
                @foreach ($featuredjobs as $job)
                    <x-job-card :$job />
                @endforeach
            </div>
            <div class="mt-6">
                {{ $featuredjobs->links() }}
            </div>
        </section>

        <section>
            <x-section-heading>Tags</x-section-heading>
            <div class="mt-6 mb-10 space-x-1">
                @foreach ($tags as $tag)
                    <x-tags :$tag />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Recent Jobs</x-section-heading>
            <div class="mt-6 space-y-6 display-flex">
                @foreach ($jobs as $job)
                    <x-job-card-wide :$job />
                @endforeach
            </div>
            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        </section>
    </div>
</x-layout>
