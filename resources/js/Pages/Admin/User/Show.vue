
<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import AppLayout from '@/Layouts/AppLayout.vue';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import JetButton from '@/Jetstream/Button.vue';
import Icon from '@/Components/Icon.vue';
import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.vue';
import XTable from '@/Components/Table.vue';
import XTd from '@/Components/TableTd.vue';
import XTh from '@/Components/TableTh.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const deleting = ref(false);

const noData = (data) => _.isEmpty(data);
const deleteUser = () => {
    deleting.value = true;

    Inertia.delete(route('admin.user.destroy', props.user.id), {
        preserveScroll: true,
        onFinish: () => deleting.value = false,
    });
};

</script>

<template>
    <AppLayout title="Usuario">
        <template #header>
            Usuarios
        </template>

        <JetActionSection class="mb-4">
            <template #content>
                <JetButton as="link" :href="route('admin.user.index')" icon="fas fa-arrow-left">
                    Volver
                </JetButton>

                <JetButton as="link" class="ml-4" :href="route('admin.user.edit', {'user': user.id})" icon="fas fa-edit">
                    Editar
                </JetButton>

                <JetConfirmsPassword
                    title="Eliminar usuario"
                    content="¿Estas seguro de eliminar este usuario? Toda la información relacionada al usuario también será eliminada. Por favor inserte su contraseña para confirmar esta acción."
                    button="Eliminar"
                    button-variant="danger"
                    @confirmed="deleteUser"
                >
                    <JetButton
                        variant="danger"
                        class="ml-4"
                        :class="{ 'opacity-25': deleting }"
                        type="button"
                        :disabled="deleting"
                        icon="far fa-trash-alt"
                    >
                        Eliminar
                    </JetButton>
                </JetConfirmsPassword>
            </template>
        </JetActionSection>
        
        <JetActionSection>
            <template #content>
                <div class="flex divide-y sm:divide-y-0 sm:divide-x flex-col sm:flex-row">
                    <div class="flex flex-1 px-5 items-center justify-center">
                        <div>
                            <div class="font-medium text-lg">
                                {{ user.name }}
                            </div>
                            <p>{{ user.role }}</p>
                        </div>
                    </div>
                    <div class="mt-6 lg:mt-0 flex-1 px-5 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left">
                            Detalles de contacto
                        </div>
                        <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                            <div class="truncate sm:whitespace-normal flex items-center">
                                <Icon class="far fa-envelope" /> {{ user.email }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 lg:mt-0 flex-1 px-5 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left">
                            Referido por:
                        </div>
                        <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                            <div class="truncate sm:whitespace-normal flex items-center">
                                <div v-if="!! user.referred_by">
                                    <Link :href="route('admin.user.show', {'user': user.referred_by.id})" class="text-indigo-500">
                                        {{ user.referred_by.name }}
                                    </Link>
                                </div>
                                <div v-else>
                                    <span v-html="nullableString(null)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </JetActionSection>

        <JetActionSection class="mt-4">
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
                                Registrado el
                            </XTh>
                            <XTh>
                                Acción
                            </XTh>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="referred in user.referrals" :key="referred.id">
                            <XTd>
                                {{ referred.name }}
                            </XTd>
                            <XTd>
                                <span v-html="nullableString(referred.email)"></span>
                            </XTd>
                            <XTd>
                                <span>{{ referred.created_at_formatted }}</span>
                            </XTd>
                            <XTd>
                                <div class="flex">
                                    <JetButton as="link" :href="route('admin.user.show', {'user': referred.id})" icon="fas fa-search" :icon-side="false" title="Ver usuario" />
                                    <JetButton as="link" :href="route('admin.user.edit', {'user': referred.id})" icon="fas fa-edit" :icon-side="false" title="Editar usuario" />
                                </div>
                            </XTd>
                        </tr>
                        <tr v-if="noData(user.referrals)">
                            <XTd colspan="4" class="text-center"><span class="text-gray-400">No se encontraron usuarios referidos</span></XTd>
                        </tr>
                    </tbody>
                </XTable>
            </template>
        </JetActionSection>
    </AppLayout>
</template>

