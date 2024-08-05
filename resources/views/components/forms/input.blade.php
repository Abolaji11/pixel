@props(['label', 'name', 'type'=>'text'])

@php
    $defaults = [
        'type' => $type,
        'id' => $name,
        'name' => $name,
        'class' => 'rounded-xl bg-white/10 border border-white/10 px-5 py-4 w-full',
        'value' => old($name),
        'label'=> $label,
    ];
@endphp

<x-forms.field :label="$label" :name="$name">
    <input {{ $attributes->merge($defaults) }}>
</x-forms.field>
