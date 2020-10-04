<template>
    <textarea
        v-model="currentValue"
        class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:shadow-outline-blue sm:text-sm transition duration-150 ease-in-out"></textarea>
</template>
<script>
export default {
    data() {
        return {
            currentValue: this.getDefaultValue()
        }
    },
    watch: {
        currentValue: function (value) {
            this.$emit('changed', value);
        }
    },
    methods: {
        getDefaultValue() {
            if (this.$slots.default && this.$slots.default.length) {
                return this.$slots.default[0].text
            }

            return ''
        }
    },
}
</script>
