import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";

export default function Errors() {
    const has = () => {
        const page = usePage();
        return Object.keys(page.props.errors).length > 0;
    }

    const showError = (errorKey) => {
        const page = usePage();
        const error = page.props.errors || {};
        return error[errorKey] || null;
    };

    const hasErrors = computed(() => has());

    return {
        hasErrors,
        showError,
    }
}
