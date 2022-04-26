
<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import vSelect from 'vue-select';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    users: {
        Type: Object,
        required: true,
    }
});

const form = useForm({
    user: props.user.user_id,
    name: props.user.name,
    email: props.user.email,
});

const submit = () => {
    form.put(route('admin.user.update', {'user': props.user.id}))
};
</script>


<template>
    <AppLayout title="Editar usuario">
        <template #header>
            Usuarios
        </template>

        <div class="grid grid-cols-6 gap-6">
            <div class="col-start-1 col-span-full md:col-start-2 md:col-span-4 lg:col-start-3 lg:col-span-2">
                <JetActionSection :boxed="false">
                    <template #title>
                        Editar usuario
                    </template>

                    <template #content>
                        <JetFormSection @submitted="submit">
                            <template #form>
                                <div class="col-span-full">
                                    <JetLabel for="user" value="Referido de" />
                                    <v-select id="user" class="border-gray-300 rounded-2xl" :options="users" label="name" v-model="form.user" :reduce="(option) => option.id">
                                        <template #search="{attributes, events}">
                                            <input
                                            class="vs__search focus:ring-0 focus:border-0 border-transparent m-0 px-3 pt-2 pb-0.5"
                                            v-bind="attributes"
                                            v-on="events"
                                            />
                                        </template>
                                    </v-select>

                                    <JetInputError :message="form.errors.role" class="mt-2" />
                                </div>

                                <div class="col-span-full">
                                    <JetLabel for="name" value="Nombre" :required="true" />
                                    <JetInput id="name" type="text" v-model="form.name" autocomplete="name" required />
                                    <JetInputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <div class="col-span-full">
                                    <JetLabel for="email" value="Email" :required="true" />
                                    <JetInput id="email" type="email" v-model="form.email" />
                                    <JetInputError :message="form.errors.email" class="mt-2" required />
                                </div>
                            </template>

                            <template #actions>
                                <JetButton as="link" :href="route('admin.user.show', {'user': user.id})" class="gray-50 mr-4" icon="fas fa-arrow-left">
                                    Volver
                                </JetButton>

                                <JetButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" icon="fas fa-paper-plane" icon-side="right">
                                    Enviar
                                </JetButton>
                            </template>
                        </JetFormSection>
                    </template>
                </JetActionSection>
            </div>
        </div>
    </AppLayout>
</template>
