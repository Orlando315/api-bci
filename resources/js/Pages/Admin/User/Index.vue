<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import XTable from '@/Components/Table.vue';
import XTd from '@/Components/TableTd.vue';
import XTh from '@/Components/TableTh.vue';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import Icon from '@/Components/Icon.vue';
import JetButton from '@/Jetstream/Button.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true,
        default: [],
    }   
});

const noData = (data) => _.isEmpty(data);
</script>

<template>
    <AppLayout title="Ususarios">
        <JetActionSection :boxed="false">
            <template #title>
                Usuarios

                <div class="float-right">
                    <JetButton as="link" :href="route('admin.user.create')" icon="fas fa-plus" title="Agregar usuario">
                        Agregar usuario
                    </JetButton>
                </div>
            </template>

            <template #content>
                <XTable>
                    <thead class="bg-gray-50">
                        <tr>
                            <XTh>
                                Nombre
                            </XTh>
                            <XTh>
                                Email
                            </XTh>
                            <XTh>
                                BCI
                            </XTh>
                            <XTh>
                                Acción
                            </XTh>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in users" :key="user.id">
                            <XTd>
                                {{ user.name }}
                            </XTd>
                            <XTd>
                                <span v-html="nullableString(user.email)"></span>
                            </XTd>
                            <XTd class="text-center">
                                {{ user.has_bci_token ? 'Sí' : 'No' }}
                            </XTd>
                            <XTd>
                                <div class="flex justify-center">
                                    <JetButton as="link" :href="route('admin.user.show', {'user': user.id})" icon="fas fa-search" :icon-side="false" title="Ver usuario" />
                                    <JetButton as="link" :href="route('admin.user.edit', {'user': user.id})" icon="fas fa-edit" :icon-side="false" title="Editar usuario" />
                                </div>
                            </XTd>
                        </tr>
                        <tr v-if="noData(users)">
                            <XTd colspan="4" class="text-center"><span class="text-gray-400">No se encontraron usuarios</span></XTd>
                        </tr>
                    </tbody>
                </XTable>
            </template>
        </JetActionSection>
    </AppLayout>
</template>
