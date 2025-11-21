export default function phone() {
    const formatNumber = (phone_number) => {
        if (!phone_number) {
            return phone_number;
        }

        const cleaned = ('' + phone_number).replace(/\D/g, '');

        if (cleaned.length !== 12) {
            return phone_number;
        }

        const match = cleaned.match(/^(\d{2})(\d{4})(\d{3})(\d{3})$/);

        if (match) {
            return `+${match[1]} ${match[2]} ${match[3]} ${match[4]}`;
        } else {
            return phone_number;
        }
    }

    return {
        formatNumber
    }
}
