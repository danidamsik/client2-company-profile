<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
    rows: {
        type: Number,
        default: 4,
    },
    textarea: {
        type: Boolean,
        default: false,
    },
    required: {
        type: Boolean,
        default: false,
    },
    autocomplete: {
        type: String,
        default: '',
    },
});

defineEmits(['update:modelValue']);

const inputClasses =
    'focus-ring mt-2 block w-full rounded-lg border-brand-line bg-white px-4 py-3 text-body-sm text-brand-ink placeholder:text-stone-400 shadow-sm transition duration-200 focus:border-brand-primary';
</script>

<template>
    <div>
        <label :for="id" class="block text-body-sm font-semibold text-brand-ink">
            {{ label }}
        </label>

        <textarea
            v-if="textarea"
            :id="id"
            :rows="rows"
            :value="modelValue"
            :placeholder="placeholder"
            :required="required"
            :class="inputClasses"
            @input="$emit('update:modelValue', $event.target.value)"
        />

        <input
            v-else
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :required="required"
            :autocomplete="autocomplete || undefined"
            :class="inputClasses"
            @input="$emit('update:modelValue', $event.target.value)"
        />

        <p v-if="error" class="mt-2 text-body-sm font-medium text-red-700">
            {{ error }}
        </p>
    </div>
</template>
