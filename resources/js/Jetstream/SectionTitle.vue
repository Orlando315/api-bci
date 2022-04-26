<script setup>
import { computed, useSlots } from 'vue';

defineProps({
    split: {
        type: Boolean,
        default: false,
    }
});

const hasTitle = computed(() => !! useSlots().title);
const hasDescription = computed(() => !! useSlots().description);
</script>

<template>
    <div :class="split ? 'md:col-span-1 flex justify-between' : 'px-4 sm:px-0'">
        <h3 class="text-lg font-medium text-gray-900" v-if="hasTitle">
            <slot name="title" />
        </h3>

        <p class="mt-1 text-sm text-gray-600" v-if="hasDescription">
            <slot name="description" />
        </p>
        
        <div v-if="split" class="px-4 sm:px-0">
            <slot name="aside" />
        </div>
    </div>
</template>
