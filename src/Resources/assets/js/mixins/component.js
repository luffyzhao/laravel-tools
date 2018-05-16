export default {
    data(){
        return {
            loading: false
        }
    },
    methods: {
        visibleChange(visible) {
            if (visible === false) {
                this.$emit('visibleChange')
            }
        }
    }
}