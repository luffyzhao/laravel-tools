<template>
    <div>
        <slot></slot>
        <div class="box-flex-list">
            <Card :bordered="false">
                <p slot="title">
                    <span>列表</span>
                </p>
                <Table :columns="columns" :data="value.data" size="small" ref="table"></Table>
                <Page :total="value.page.total" size="small" :current="value.page.current" :page-size="value.page.page_size" show-total @on-change="change"></Page>
            </Card>
        </div>
    </div>
</template>

<script>
    export default {
        name: "my-lists",
        props: {
            value: {
                type: Object,
                default: () => {return {data:[], page:{total:100, current: 1, page_size: 20}}}
            },
            columns: {
                type: Array,
                default: () => {return []}
            }
        },
        methods: {
            change(v){
                this.$emit('change', v);
            }
        }
    }
</script>

<style scoped>

</style>