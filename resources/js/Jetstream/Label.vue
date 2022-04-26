<script setup>
import { inject } from 'vue';

const props = defineProps({
    value: String,
    required: {
        type: Boolean,
        default: false,
    },
});

const showRequiredLabelMessage = inject('showRequiredLabelMessage', () => {});

if(props['required']){
    showRequiredLabelMessage();
}
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span class="text-red-700" v-if="required"> *</span>
    </label>
</template>
