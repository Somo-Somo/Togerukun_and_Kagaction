<template>
    <div>
        <v-dialog
            v-model="deletingConfirmationDialog"
            @click:outside="$emit('onClickCancel')"
            width="500"
        >
            <v-card>
                <v-card-title class="text-h6"> 削除 </v-card-title>
                <v-card-text>
                    「{{
                        selectedDeletingItemName
                    }}」を削除してもよろしいでしょうか。
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey" text @click="$emit('onClickCancel')">
                        キャンセル
                    </v-btn>
                    <v-btn
                        color="error"
                        text
                        @click="$emit('deleteItem')"
                        :loading="loading"
                        :disabled="loading"
                    >
                        OK
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    props: {
        deletingConfirmationDialog: {
            type: Boolean,
        },
        selectedDeletingItemName: {
            type: String,
        },
        loading: {
            type: Boolean,
        },
    },
};
</script>
