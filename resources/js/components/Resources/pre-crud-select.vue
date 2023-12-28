<template>
    <div>
        <div class="vstack-crud-card mb-3 p-3 dark:bg-gray-800 dark:border-none">
            <div class="flex justify-end items-center gap-5 flex-wrap">
                <button class="vstack-btn primary" :disabled="loading || !selected" @click="submit">
                    {{ loading ? 'Aguarde ...' : btn_text }}
                    <i :class="`${icon} ml-2`"></i>
                </button>
            </div>
        </div>
        <div class="w-full">
            <resource-crud-card :label="card_title">
                <div class="row-responsive-table">
                    <table class="table table-crud mb-0 w-full">
                        <tbody>
                            <custom-resource-component :show_label="true" :description="description" :label="title">
                                <el-select class="w-full" clearable v-model="selected" filterable v-loading="loading"
                                    loading-text="Carregando..." :popper-append-to-body="false">
                                    <el-option v-for="(item, i) in items" :key="i" :label="item.name" :value="item.code" />
                                </el-select>
                            </custom-resource-component>
                        </tbody>
                    </table>
                </div>
            </resource-crud-card>
        </div>
    </div>
</template>
<script>
export default {
    props: ['card_title', 'title', 'description', 'model', 'postback', 'field', 'btn_text', 'icon'],
    data() {
        return {
            loading: false,
            selected: null,
            items: []
        }
    },
    created() {
        this.getContent()
    },
    methods: {
        submit() {
            this.loading = true;
            window.location.href = `${this.postback}?${this.field}=${this.selected}`;
        },
        getContent() {
            this.$http.get(`/vstack/json-api`, {
                params: {
                    model: this.model,
                }
            }).then(({ data }) => {
                this.items = data;
            }).catch(error => {
                console.log(error);
            }).finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>