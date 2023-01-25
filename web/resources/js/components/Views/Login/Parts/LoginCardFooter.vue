<template>
    <div>
        <div class="my-4 d-flex">
            <p class="ma-0 align-self-center" style="font-size: 14px">
                ※
                現在こちらのサービスはテスト版として運用させていただいています。
            </p>
        </div>
        <div class="my-4 d-flex">
            <p class="ma-0 align-self-center" style="font-size: 14px">
                {{
                    isLoginForm
                        ? "アカウントをお持ちでない方"
                        : "すでにアカウントをお持ちですか？"
                }}
            </p>
            <v-btn
                class="align-self-center font-weight-bold"
                color="primary"
                @click="switchForm"
                text
                small
            >
                {{ isLoginForm ? "会員登録" : "ログイン" }}
            </v-btn>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        isLoginForm: {
            type: Boolean,
        },
    },
    computed: {},
    methods: {
        switchForm() {
            this.$emit("switchForm");
        },
    },
};
</script>
