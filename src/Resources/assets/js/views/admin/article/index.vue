<template>
    <my-lists @change="search" v-model="data" :columns="columns">
        <Card :bordered="false">
            <p slot="title">搜索</p>
            <Form ref="formInline" :model="searchForm" inline :label-width="60">
                <FormItem label="单号">
                    <Input v-model="searchForm.number"></Input>
                </FormItem>
                <FormItem :label-width="1">
                    <Button @click="search(1)">查询</Button>
                    <Button type="ghost" @click="current('Update')">添加</Button>
                    <Button type="ghost" @click="current('Update')">导入</Button>
                </FormItem>
            </Form>
        </Card>
        <component :is="componentCurrent" v-on:visibleChange="componentChange" :data="componentRow"></component>
    </my-lists>
</template>

<script>
    import Lists from '@/mixins/lists'
    import MyLists from "../../../components/my-lists";
    import Update from './update'

    export default {
        components: {MyLists, Update},
        name: "index",
        mixins:[Lists],
        data(){
            return {
                columns: [
                    {
                        title: 'Name',
                        key: 'name'
                    }
                ],
                data: {
                    data:[{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},{name: 'dfdssaf'},],
                }
            }
        },
        methods: {
            search(v){
            }
        }
    }
</script>

<style scoped>

</style>