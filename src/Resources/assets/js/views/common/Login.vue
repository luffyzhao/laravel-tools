<template lang="html">
  <login-lock>
    <p slot="title"><Icon type="log-in"></Icon> 欢迎登录</p>
    <Form slot="form" ref="form" :model="form">
      <FormItem prop="email" :rules="{required: true, message: '邮箱格式不正确！', type: 'email', min: 6, max:20, trigger: 'blur'}">
        <Input type="text" v-model="form.email" autocomplete="off" placeholder="E-mail">
        <Icon type="ios-email-outline" slot="prepend"></Icon>
        </Input>
      </FormItem>
      <FormItem prop="password" :rules="{required: true, message: '密码不能为空！', min: 6, max:20, trigger: 'blur'}">
        <Input type="password" v-model="form.password" autocomplete="off" placeholder="Password" @on-enter="login('form')">
        <Icon type="ios-locked-outline" slot="prepend"></Icon>
        </Input>
      </FormItem>
      <FormItem>
        <Button type="primary" long @click="login('form')">登录</Button>
      </FormItem>
    </Form>
  </login-lock>
</template>

<script lang="babel">
  import loginLock from '@/views/common/components/login-lock.vue'

  export default {
    data () {
      return {
        form: {
          email:'',
          password:''
        }
      }
    },
    components: {
      loginLock
    },
    methods: {
      login (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            this.$util.cache.set('token', 'your token')
            this.$router.replace({name: 'admin.home'})
          }
        });
      }
    }
  }
</script>
