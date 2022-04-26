<script setup>
import { computed, useSlots, ref, provide } from 'vue';
import JetSectionTitle from '@/Jetstream/SectionTitle.vue';

defineEmits(['submitted']);

const hasRequiredLabel = ref(false);

const props = defineProps({
    split: {
        type: Boolean,
        default: false,
    },
    transparent: {
        type: Boolean,
        default: false,
    },
    requiredText: {
        type: Boolean,
        default: true,
    }
});

const hasActions = computed(() => !! useSlots().actions);
const hasTitleOrDescription = computed(() => (!! useSlots().title || !! useSlots().description));
const hasFormRequiredLabel = computed(() => hasRequiredLabel.value);

const showRequiredLabelMessage = () => hasRequiredLabel.value = true;

provide('showRequiredLabelMessage', showRequiredLabelMessage);
</script>

<template>
    <div :class="{'md:grid md:grid-cols-3 md:gap-6': split}">
        <JetSectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </JetSectionTitle>

        <div :class="{'mt-5': hasTitleOrDescription && (! split), 'md:col-span-2': split}">
            <form @submit.prevent="$emit('submitted')">
                <div
                    class="px-4 py-5 sm:p-6"
                    :class="{'sm:rounded-t-xl': hasActions, 'sm:rounded-xl' : ! hasActions, 'bg-white shadow': ! transparent}"
                >
                    <div class="grid grid-cols-6 gap-6">
                        <slot name="form" />
                    </div>

                    <p class="mt-4 text-gray-500" v-if="hasFormRequiredLabel && requiredText"><span class="text-red-700">*</span> campos requeridos</p>
                </div>

                <div
                    v-if="hasActions"
                    :class="{'bg-gray-50 shadow': ! transparent}"
                    class="flex items-center justify-end px-4 py-3 text-right sm:px-6 rounded-b-md"
                >
                    <slot name="actions" />
                </div>
            </form>
        </div>
    </div>
</template>
