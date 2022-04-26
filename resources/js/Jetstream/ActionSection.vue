<script setup>
import { computed, useSlots } from 'vue';
import JetSectionTitle from './SectionTitle.vue';

defineProps({
    boxed: {
        type: Boolean,
        default: true,
    },
    split: {
        type: Boolean,
        default: false,
    }
});

const hasTitle = computed(() => !! useSlots().title);
const hasDescription = computed(() => !! useSlots().description);
const hasTitleOrDescription = computed(() => (hasTitle.value || hasDescription.value));
</script>

<template>
    <div :class="{'md:grid md:grid-cols-3 md:gap-6': split}">
        <JetSectionTitle>
            <template #title v-if="hasTitle">
                <slot name="title" />
            </template>
            <template #description v-if="hasDescription">
                <slot name="description" />
            </template>
        </JetSectionTitle>

        <div :class="{'mt-5': hasTitleOrDescription && (! split), 'md:col-span-2': split}">
            <div class="bg-white shadow-lg rounded-xl" :class="boxed ? 'px-4 py-5 sm:p-6' : ''">
                <slot name="content" />
            </div>
        </div>
    </div>
</template>
