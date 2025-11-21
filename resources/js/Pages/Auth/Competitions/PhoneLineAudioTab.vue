<script setup>
import Tip from "@/Components/Tip/Tip.vue";
import PhoneLineAudioDetails from "@/Pages/Auth/Competitions/PhoneLineAudioDetails.vue";

const props = defineProps({
    phoneLineData: Object,
    defaultAudio: Object,
    competitionFiles: Object,
})

// get default audio and based on the phone line price swap out CALL_COST_WARNING & CAPPING_MESSAGE with appropriate £2.00 or £2.50 ones.
// note - if the price is £1.50 then just use the default CALL_COST_WARNING & CAPPING_MESSAGE.
const getDefaultAudioFiltered = (defaultAudio, cost) => {
    const audio = { ...defaultAudio };

    const overrideKeys = [
        'CALL_COST_WARNING_2_00',
        'CAPPING_MESSAGE_2_00',
        'CALL_COST_WARNING_2_50',
        'CAPPING_MESSAGE_2_50',
    ];

    if (cost === '2.00') {
        audio.CALL_COST_WARNING = audio.CALL_COST_WARNING_2_00;
        audio.CAPPING_MESSAGE = audio.CAPPING_MESSAGE_2_00;
    } else if (cost === '2.50') {
        audio.CALL_COST_WARNING = audio.CALL_COST_WARNING_2_50;
        audio.CAPPING_MESSAGE = audio.CAPPING_MESSAGE_2_50;
    }

    overrideKeys.forEach(key => delete audio[key]);

    return audio;
};

const defaultAudioFiltered = getDefaultAudioFiltered(props.defaultAudio, props.phoneLineData.attributes.cost);

let competitionAudio = props.competitionFiles
    .filter((item) => item.attributes.competition_phone_line_id === null)
    .reduce((acc, item) => {
        acc[item.attributes.type] = item.attributes.external_id;
        return acc;
    }, {});

const phoneLineAudio = props.competitionFiles
    .filter((item) => item.attributes.competition_phone_line_id === props.phoneLineData.id)
    .reduce((acc, item) => {
        acc[item.attributes.type] = item.attributes.external_id;
        return acc;
    }, {});

const filteredAudio = {
    ...defaultAudioFiltered, // lowest priority
    ...competitionAudio,   // middle priority
    ...phoneLineAudio      // highest priority
}

const suppliedBy = (external_id) => {
    if(Object.values(phoneLineAudio).includes(external_id)){
        return 'phone line'
    }

    if(Object.values(competitionAudio).includes(external_id)){
        return 'competition'
    }

    return 'default';
}

const finalisedAudioData = Object.entries(filteredAudio).map(([audio_type, external_id]) => ({
    audio_type,
    external_id,
    supplied_by: suppliedBy(external_id)
}));
</script>

<template>
    <div class="border rounded p-4">
        <p class="font-bold text-blue-600">Audio</p>

        <div class="hidden xl:block mt-4">
            <div
                class="grid grid-cols-12 gap-4 p-3 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 dark:text-gray-400"
            >
                <p class="col-span-2">
                    SHOUT ID
                    <tip description="ID of the audio on the Shout Server."></tip>
                </p>

                <p class="col-span-3">
                    Type
                    <tip description="The audio type."></tip>
                </p>
            </div>
        </div>
        <template v-for="(data, index) in finalisedAudioData">
            <PhoneLineAudioDetails
                :data="data"
                :even="!!(index % 2)"
                :index="index"
            >
            </PhoneLineAudioDetails>
        </template>
    </div>
</template>

