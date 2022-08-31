<template>
    <div>
        <v-text-field
            v-model="formValue.name"
            v-if="!isLoginForm"
            :error-messages="errorMessages ? errorMessages.name : null"
            autofocus
            dense
            height="48px"
            outlined
            placeholder="ユーザ名"
        ></v-text-field>

        <v-text-field
            v-model="formValue.email"
            :error-messages="errorMessagesEmail"
            autofocus
            dense
            height="48px"
            outlined
            placeholder="メールアドレス"
        ></v-text-field>

        <v-text-field
            v-model="formValue.password"
            :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'"
            :type="passwordShow ? 'text' : 'password'"
            :error-messages="errorMessagesPassword"
            dense
            height="48px"
            name="input-password"
            outlined
            placeholder="パスワード"
            @click:append="passwordShow = !passwordShow"
        ></v-text-field>
        <div
            v-if="!isLoginForm"
            style="position: relative"
            :style="!errorMessages ? 'top: -16px;' : 'top: -8px;'"
        >
            <ul>
                <li>半角英数字・記号8文字以上</li>
                <li>半角英字・数字それぞれ一文字以上含む</li>
            </ul>
        </div>
    </div>
</template>

<script>
import SuccessAlert from "../../../Common/Parts/Atom/SuccessAlert.vue";
import LoginCardTitle from "../Parts/LoginCardTitle.vue";

export default {
    components: {
        SuccessAlert,
        LoginCardTitle,
    },
    data: () => ({
        passwordShow: false,
    }),
    props: {
        isLoginForm: {
            type: Boolean,
        },
        loginAndRegisterOfErrorMessages: {
            type: Object,
        },
        formValue: {
            type: Object,
        },
    },
    computed: {
        errorMessages() {
            return this.isLoginForm
                ? this.loginAndRegisterOfErrorMessages.loginErrorMessages
                : this.loginAndRegisterOfErrorMessages.registerErrorMessages;
        },
        errorMessagesEmail() {
            return this.errorMessages.email
                ? this.errorMessages.email[0]
                : null;
        },
        errorMessagesPassword() {
            return this.errorMessages.password
                ? this.errorMessages.password[0]
                : null;
        },
        formValue: {
            get() {
                return this.formValue;
            },
            set(newVal) {
                this.$emit("setFormValue", newVal);
            },
        },
    },
};
</script>
