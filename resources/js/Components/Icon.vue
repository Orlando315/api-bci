
<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    class: {
        type: String,
        required: true,
    },
    side: {
        type: [Boolean, String],
        default: 'left',
    },
});
const classes = ref(props.class);
const iconIsRight = computed(() => props.side == 'right');
const iconIsLeft = computed(() => ! iconIsRight.value);
const addMargin = computed(() => props.side !== false);

watch(
    () => props.class,
    (iconClass) => {
        classes.value = iconClass;
    },
);
</script>

<template>
    <i :class="{[classes]: true, 'mr-2': iconIsLeft && addMargin, 'ml-2': iconIsRight && addMargin}"></i>
</template>
