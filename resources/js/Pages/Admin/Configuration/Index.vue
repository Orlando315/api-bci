<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import XTable from '@/Components/Table.vue';
import XTd from '@/Components/TableTd.vue';
import XTh from '@/Components/TableTh.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    referralsCommission: {
        type: Object,
        required: true,
        default: [],
    },
    poolReward: {
        type: Object,
        required: true,
        default: [],
    },
    itemsAmountLimit: {
        type: Number,
        required: true,
    }
});

const form = useForm({
    referido: props.referralsCommission,
    pool_reward: props.poolReward,
});

const submit = () => form.patch(route('admin.configuration'));

const referralsCount = computed(() => Object.values(props.referralsCommission).length);
const calculateTotal = (index) => _.round(Object.values(form.referido).reduce((sum, commisions) => sum + parseFloat(commisions[index] || 0), 0), 2);
const calculateRestante = (index) => _.round(100 - (calculateTotal(index) + form.pool_reward[index]), 2);
const verifyAmount = () => {

};
</script>

<template>
    <AppLayout title="Configuración">
        <JetActionSection :boxed="false">
            <template #title>
                Configuración
            </template>

            <template #content>
                <JetFormSection @submitted="submit">
                    <template #form>
                        <div class="col-span-full">
                            <XTable>
                                <thead class="bg-gray-50">
                                    <tr>
                                        <XTh class="text-center" :colspan="itemsAmountLimit + 2">
                                            COMPRAS
                                        </XTh>
                                    </tr>
                                    <tr>
                                        <XTh class="border-0"></XTh>
                                        <XTh></XTh>
                                        <XTh class="text-center" v-for="itemAmount in itemsAmountLimit" :key="itemAmount">
                                            {{ itemAmount }}
                                        </XTh>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <XTh :rowspan="referralsCount + 1" class="bg-gray-50 border-r" style="writing-mode:sideways-lr">
                                            Referidos
                                        </XTh>
                                    </tr>
                                    <tr v-for="(referralComission, indexReferral) in referralsCommission" :key="indexReferral">
                                        <XTd>
                                            Referido #{{ indexReferral }}
                                        </XTd>
                                        <XTd v-for="(commission, indexCommission) in referralComission" :key="indexCommission">
                                            <JetInput
                                                type="number"
                                                step="0.1"
                                                min="0"
                                                max="99"
                                                v-model.number="form.referido[indexReferral][indexCommission]"
                                                :has-error="!! form.errors['referido.'+indexReferral+'.'+indexCommission]"
                                                :value="commission"
                                                required
                                            />
                                        </XTd>
                                    </tr>
                                    <tr>
                                        <XTd colspan="2">
                                            Pool reward
                                        </XTd>
                                        <XTd v-for="(reward, indexReward) in poolReward" :key="indexReward">
                                            <JetInput type="number" step="0.1" min="0" max="99" v-model.number="form.pool_reward[indexReward]" :has-error="!! form.errors['pool_ward.'+indexReward]" :value="reward" required />
                                        </XTd>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <XTh colspan="2">
                                            Team
                                        </XTh>
                                        <XTd v-for="amountItems in itemsAmountLimit" :key="amountItems">
                                            {{ calculateRestante(amountItems) }}
                                        </XTd>
                                    </tr>
                                </tfoot>
                            </XTable>

                            <JetValidationErrors class="mt-4" />
                        </div>
                    </template>

                    <template #actions>
                        <JetButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" icon="fas fa-" icon-side="right">
                            Guardar
                        </JetButton>
                    </template>
                </JetFormSection>
            </template>
        </JetActionSection>
    </AppLayout>
</template>
