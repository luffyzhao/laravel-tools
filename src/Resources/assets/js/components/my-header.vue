<template>
    <Row>
        <Col :span="12">
            <div class="logo"></div>
        </Col>
        <Col :span="12" style="text-align: right;">
            <my-header-ownspace></my-header-ownspace>
        </Col>
    </Row>

</template>

<script>
    import MyHeaderOwnspace from "./my-header-ownspace";
    import MyHeaderIcon from "./my-header-icon";

    export default {
        components: {
            MyHeaderIcon,
            MyHeaderOwnspace},
        name: "my-header"
    }
</script>

<style lang="scss">
</style>