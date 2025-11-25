import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export default function useOrganisationSelect() {
    const organisations = computed(() => {
        return usePage().props.auth.organisations ?? {};
    });

    const organisationSelectData = computed(() => {
        return Object.values(organisations.value).map(org => ({
            label: org.name,
            value: org.id,
        }));
    });

    return {
        organisations,
        organisationSelectData,
    };
}
