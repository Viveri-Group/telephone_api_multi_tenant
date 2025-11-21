export default function HTTPMixin() {
    const attemptsBadge = (status, attempts) => {
        let statusSuccess = status.startsWith(2);
        let statusColor = 'bg-gray-200'

        if (statusSuccess && attempts > 1) {
            statusColor = 'bg-orange-500 text-white';
        }

        if (!statusSuccess) {
            statusColor = 'bg-red-500 text-white';
        }

        return statusColor;
    }

    const responseText = (status) => {
        if (status.startsWith(2)) {
            return 'text-green-500';
        }

        if (status.startsWith(3)) {
            return 'text-orange-500';
        }

        if (status.startsWith(4) || status.startsWith(5)) {
            return 'text-red-500';
        }
    }

    const responseBadge = (status) => {
        if (status.startsWith(2)) {
            return 'bg-green-500 text-white';
        }

        if (status.startsWith(3)) {
            return 'bg-orange-500 text-white';
        }

        if (status.startsWith(4) || status.startsWith(5)) {
            return 'bg-red-500 text-white';
        }
    }

    const responseSideBorder = (status) => {
        if (status.startsWith(2)) {
            return 'border-green-300';
        }

        if (status.startsWith(3)) {
            return 'border-blue-500';
        }

        if (status.startsWith(4) || status.startsWith(5)) {
            return 'border-red-300';
        }
    }

    const requestDuration = (time) => {
        if (time <= 500) {
            return 'text-green-500';
        }

        if (time <= 1000) {
            return 'text-orange-500';
        }

        if (time > 1000) {
            return 'text-red-500';
        }
    }

    return {
        attemptsBadge,
        responseBadge,
        responseSideBorder,
        responseText,
        requestDuration
    }
}
