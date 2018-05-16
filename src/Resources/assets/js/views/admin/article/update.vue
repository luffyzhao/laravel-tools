<template>
    <Modal :value="true" title="Title" :loading="loading" :mask-closable="false" @on-visible-change="visibleChange" :transfer="false">
        <p><Input v-model="value"></Input></p>
    </Modal>
</template>

<script>
    import Component from '@/mixins/component'
    export default {
        name: "update",
        mixins:[Component],
        data(){
            return {
                value: 'adsfasd'
            }
        }
    }
</script>

<style scoped>

</style>