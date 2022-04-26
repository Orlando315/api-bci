<script setup>
import { computed, useAttrs } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import Icon from '@/Components/Icon.vue';

const attrs = useAttrs();

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    href: {
        type: String,
        deafult: '#',
    },
    variant: {
        type: [String, Boolean],
        default: 'secondary',
    },
    icon: {
        type: String,
    },
    iconSide: {
        type: [String, Boolean],
        default: 'left',
    },
    as: {
        default: 'button',
    },
});

const variantClasses = computed(() => {
    switch (props.variant) {
        case false:
            return '';
            break;

        case 'danger':
            return 'bg-red-600 hover:bg-red-500 focus:border-red-700 focus:ring-red-200 border-transparent text-white hover:text-white active:bg-red-600';
            break;

        case 'secondary':
        default:
            return 'bg-white focus:border-gray-300 focus:ring-gray-300 border-gray-300 hover:text-white hover:bg-gray-700 active:bg-gray-900';
            break;
    }
});
</script>

<template>
    <a v-if="(as == 'a')" v-bind="attrs" :href="href" :class="variantClasses" class="inline-flex items-center justify-center px-4 py-2 border rounded-md transition-colors duration-300 font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
        <slot name="icon" v-if="!! icon && (iconSide === false || iconSide == 'left')"><Icon :class="icon" :side="iconSide" /></slot>
        <slot />
        <slot name="icon" v-if="!! icon && iconSide == 'right'"><Icon :class="icon" side="right" /></slot>
    </a>

    <button v-if="(as == 'button')" v-bind="attrs" :type="type" :class="variantClasses" class="inline-flex items-center justify-center px-4 py-2 border rounded-md transition-colors duration-300 font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
        <slot name="icon" v-if="!! icon && (iconSide === false || iconSide == 'left')"><Icon :class="icon" :side="iconSide" /></slot>
        <slot />
        <slot name="icon" v-if="!! icon && iconSide == 'right'"><Icon :class="icon" side="right" /></slot>
    </button>

    <Link v-else v-bind="attrs" :href="href" :class="variantClasses" class="inline-flex items-center justify-center px-4 py-2 border rounded-md transition-colors duration-300 font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
        <slot name="icon" v-if="!! icon && (iconSide === false || iconSide == 'left')"><Icon :class="icon" :side="iconSide" /></slot>
        <slot />
        <slot name="icon" v-if="!! icon && iconSide == 'right'"><Icon :class="icon" side="right" /></slot>
    </Link>
</template>
