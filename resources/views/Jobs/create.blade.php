<x-layout>
<x-page-heading>New Jobs</x-page-heading>

<x-forms.form method="post" action="/jobs">

    <x-forms.input label="Title" name="title" placeholder="Mechanical Engineer" />
    <x-forms.input label="Salary" name="salary" placeholder="$100,000"   />
    <x-forms.input label="Location" name="location" placeholder="Winter Park, Florida" />
    <x-forms.select label="Schedule" name="schedule"> 
        <option>Part Time</option>
        <option>Full Time</option>
    </x-forms.select>
    
    <x-forms.input label="URL" name="url" placeholder="https://www.coinscreed.com" />
    <x-forms.checkbox label="feature (Costs Extra)" name="featured" />

    <hr />

    <x-forms.input label="Tags (comma seperated)" name="tags" placeholder="laracasts, video, education" />

    <x-forms.button style="margin-bottom: 10px">Publish</x-forms.button>

    

</x-forms.form>

















</x-layout>