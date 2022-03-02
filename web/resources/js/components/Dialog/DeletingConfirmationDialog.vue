<template>
    <div>
        <v-dialog
        v-model="deletingConfirmationDialog"
        width="500"
        >
        <v-card>
            <v-card-title class="text-h6">
            削除
            </v-card-title>
            <v-card-text>
            プロジェクト: 「{{selectedDeletingItem.name}}」を削除してもよろしいでしょうか。
            共有している場合は共有者のプロジェクト一覧からも削除が行われます。
            </v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="grey"
                text
                @click="$emit('cancel')"
            >
                キャンセル
            </v-btn>
            <v-btn
                color="error"
                text
                @click="$emit('deleteItem')"
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
    selectedDeletingItem: {
      type: Object,
    },
  },
};
</script>