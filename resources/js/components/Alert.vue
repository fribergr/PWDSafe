<template>
    <div :class="allclasses" role="alert">
        <slot></slot>
    </div>
</template>
<script>
export default {
    props: {
        theme: {
            type: String,
            default: 'info',
        },
        classes: {
            type: String,
            default: 'max-w-lg',
        }
    },
    data() {
        return {
            colors: {
                info: 'border-blue-100 bg-blue-200 text-blue-800',
                secondary: 'border-gray-100 bg-gray-200 text-gray-800',
                danger: 'border-red-100 bg-red-200 text-red-800',
                success: 'border-green-100 bg-green-200 text-green-800',
            }
        }
    },
    computed: {
        allclasses: function() {
            return 'border p-4 rounded-md ' + this.colors[this.theme] + ' ' + this.classes;
        }
    }
}
</script>
