<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';

</script>

<template>
    <JetActionSection :split="true">
        <template #title>
            Integración con BCI
        </template>

        <template #description>
            
        </template>

        <template #content>
            <JetButton as="link" :href="route('integration.bci')">
                Guardar
            </JetButton>
        </template>
    </JetActionSection>
</template>
