<template>
    <div class="relative text-center w-10 h-10 cursor-pointer" @click="toggle">
        <p
            class="inline-block mt-2 text-[#2ec162] font-bold"
            v-text="time + 's'"
            v-if="showNumbers"
            ref="countdownNumbersRef"
        ></p>
        <svg class="absolute top-0 right-0 w-10 h-10" style="transform: rotateY(-180deg) rotateZ(-90deg)">
            <circle r="18" cx="20" cy="20" :id="timerBarId" ref="circleBarRef"></circle>
        </svg>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    emits: ['count-down-ended'],

    props: {
        countDownValue: {
            default: 20,
        },
        autoStart: {
            type: Boolean,
            default: true,
        },
        autoRestart: {
            type: Boolean,
            default: false,
        },
        colour: {
            type: String,
            default: '#2ec162',
        },
        disabledColour: {
            type: String,
            default: '#D1D5DB',
        },
        showNumbers: {
            type: Boolean,
            default: true,
        },
        turnAmberAt: {
            default: null,
        },
        turnRedAt: {
            default: null,
        },
        finalStopAfter: {
            default: false,
        },
        debug: {
            type: Boolean,
            default: false,
        },
    },

    setup() {
        const circleBarRef = ref(null);
        const countdownNumbersRef = ref(null);
        return { circleBarRef, countdownNumbersRef };
    },

    data() {
        return {
            masterCount: 0,
            time: this.countDownValue,
            timerId: null,
            masterTimerId: null,
            started: false,
            timerBarId: '',
            stoppedByMaster: false,
        };
    },

    mounted() {
        if (this.autoStart) {
            this.setColour(this.colour);
            this.start();
        } else {
            this.setColour(this.disabledColour);
        }
    },

    methods: {
        toggle() {
            this.started = !this.started;
            this.started ? this.start() : this.stop();
        },

        start() {
            this.setColour(this.colour);
            this.circleBarRef.style.animationDuration = `${this.time}s`;
            this.started = true;
            this.timerBarId = 'timer-bar';
            this.logDetails();
            this.timerId = setInterval(this.countDown, 1000);

            if (this.finalStopAfter && !this.masterTimerId) {
                this.masterTimerId = setInterval(this.masterCountDown, 1000);
                this.masterCount = 0;
            }
        },

        logDetails() {
            if (this.debug)
                console.info(
                    'CountDownTimer' +
                        '\nstarted:\t\t\t' +
                        this.started +
                        '\nduration:\t\t\t' +
                        this.time +
                        '\nauto-start:\t\t\t' +
                        this.autoStart +
                        '\nauto-restart:\t\t' +
                        this.autoRestart +
                        '\nfinal-stop-after:\t' +
                        this.finalStopAfter
                );
        },

        stop() {
            this.setColour(this.disabledColour);
            this.started = false;
            clearInterval(this.timerId);
            this.timerId = null;
            this.timerBarId = '';
            this.reset();
        },

        reset() {
            this.time = this.$props.countDownValue;
        },

        restart() {
            this.setColour(this.colour);
            this.timerBarId = '';
            this.reset();
            this.timerBarId = 'timer-bar';
        },

        setColour(colour) {
            if (colour && this.circleBarRef && this.countdownNumbersRef) {
                this.circleBarRef.style.stroke = colour;
                this.countdownNumbersRef.style.color = colour;
            }
        },

        colourCheck() {
            if (this.turnAmberAt && this.time === Number(this.turnAmberAt)) {
                this.setColour('#f2ce02');
            }

            if (this.turnRedAt && this.time === Number(this.turnRedAt)) {
                this.setColour('#ff0a0a');
            }
        },

        countDown() {
            if (this.time === 1) {
                this.autoRestart ? this.restart() : this.stop();
                this.$emit('count-down-ended');

                return false;
            }

            if (this.time > 1) {
                this.time--;
                this.colourCheck();
            }
        },

        masterCountDown() {
            if (this.masterCount === Number(this.finalStopAfter)) {
                this.stoppedByMaster = true;
                this.stop();
                clearInterval(this.masterTimerId);
                this.masterTimerId = null;

                return false;
            }

            this.masterCount++;
        },
    },
};
</script>

<style scoped>
svg circle {
    fill: none;
    stroke-width: 3px;
}

#timer-bar {
    stroke-dasharray: 113px;
    stroke-dashoffset: 0px;
    stroke-linecap: round;
    stroke-width: 3px;
    fill: none;
    animation: countdown 10s linear infinite forwards;
}

@keyframes countdown {
    from {
        stroke-dashoffset: 0px;
    }
    to {
        stroke-dashoffset: 113px;
    }
}
</style>
