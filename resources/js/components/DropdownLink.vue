<template>
    <a :href="href" :class="allclasses" role="menuitem">
        <slot></slot>
    </a>
</template>
<script>
export default {
    props: {
        userClasses: {
            type: String,
            default: '',
        },
        href: {
            type: String
        }
    },
    data() {
        return {
            classes: 'block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out'
        }
    },
    computed: {
        allclasses: function() {
            return this.classes + ' ' + this.userClasses;
        }
    }
}
</script>
