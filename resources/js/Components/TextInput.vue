<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="focus-ring rounded-lg border-brand-line bg-white px-4 py-3 text-body-sm text-brand-ink shadow-sm transition duration-200 placeholder:text-stone-400 hover:border-brand-primary focus:border-brand-primary"
        v-model="model"
        ref="input"
    />
</template>
