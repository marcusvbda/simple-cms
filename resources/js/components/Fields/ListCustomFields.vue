<!-- eslint-disable vue/html-closing-bracket-newline -->
<template>
    <div class="list-custom-fields">
        <div class="section-field field" v-for="(field, i) in form[field]" :key="i">
            <el-input placeholder="Nome do campo" v-model="field.name" :maxlength="255" class="w-full" size="mini" />
            <el-select v-model="field.type" placeholder="Tipo do campo" class="w-full" size="mini">
                <el-option v-for="(t, i) in types" :key="i" :label="t.label" :value="t.value" />
            </el-select>
            <div class="options-input" v-if="field.type == 'select'">
                <el-tag :key="opIndex" v-for="(op, opIndex) in field.options" closable :disable-transitions="false"
                    size="min" @close="handleCloseOption(i, opIndex)">
                    {{ op }}
                </el-tag>
                <el-input class="input-new-tag" v-if="field.options_settings.visible"
                    v-model="field.options_settings.inputValue" ref="saveTagInput" size="mini"
                    @keyup.enter.native="handleInputConfirmOp(i)" @blur="handleInputConfirmOp(i)" />
                <el-button v-else class="button-new-tag" size="mini" @click="showInputOp(i)">+</el-button>
            </div>
            <el-checkbox v-if="field.type == 'select'" v-model="field.multiple" size="mini">Seleção múltipla</el-checkbox>
            <el-checkbox v-model="field.required" size="mini">Obrigatório</el-checkbox>

            <a href="#" class="btn-close" @click.prevent="deleteRow(i)">
                <i class="fa fa-times text-danger" />
            </a>
        </div>
        <a href="#" @click.prevent="addNew" class="section-field add">
            <i class="fa fa-plus" />
            <span>Adicionar novo campo</span>
        </a>
    </div>
</template>
<script>
export default {
    props: ['field', 'default_value', 'form'],
    data() {
        return {
            types: [
                { label: 'Texto', value: 'text' },
                { label: 'Número', value: 'number' },
                { label: 'Data', value: 'date' },
                { label: 'Seleção', value: 'select' },
            ],
            initialField: {
                name: '',
                type: 'text',
                options: [],
                multiple: false,
                required: false,
                options_settings: {
                    visible: false,
                    inputValue: '',
                }
            }
        }
    },
    created() {
        this.initField()
    },
    methods: {
        showInputOp(i) {
            this.form[this.field][i].options_settings.visible = true
        },
        handleInputConfirmOp(i) {
            const fields = this.form[this.field];
            const inputValue = this.form[this.field][i].options_settings.inputValue
            if (inputValue) {
                fields[i].options.push(inputValue);
            }

            this.form[this.field][i].options_settings.visible = false
            this.form[this.field][i].options_settings.inputValue = ''

            this.$set(this.form, this.field, fields)
        },
        handleCloseOption(i, opIndex) {
            let fields = this.form[this.field];
            fields[i].options = fields[i].options.filter((f, index) => index != opIndex)
            this.$set(this.form, this.field, fields)
        },
        deleteRow(i) {
            let fields = this.form[this.field];
            fields = fields.filter((f, index) => index != i)
            this.$set(this.form, this.field, fields)
        },
        initField() {
            const defaultValue = (this.default_value || []).map((x) => {
                return {
                    ...Object.assign({}, this.initialField),
                    ...x
                }
            })

            this.$set(this.form, this.field, defaultValue)
        },
        addNew() {
            const fields = this.form[this.field];
            fields.push(Object.assign({}, this.initialField))
            this.$set(this.form, this.field, fields)
        }
    }
}
</script>
<style lang="scss">
.btn-close {
    position: absolute;
    background-color: #d20e0e;
    top: -10px;
    right: -10px;
    border-radius: 100%;
    height: 20px;
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 10px;
    transition: .5s;
    color: white;

    &:hover {
        background-color: #b10000;
        color: white;
        cursor: pointer;
    }

}

.list-custom-fields {
    display: flex;
    flex-direction: column;
    gap: 15px;
    justify-content: center;
    align-items: center;
    width: 100%;

    .section-field {
        position: relative;
        padding: 20px 15px;
        min-height: 70px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #b6b6b6;
        gap: 10px;
        font-size: 14px;
        border-color: #dcdcdc;
        border-style: solid;
        border-width: 1px;
        border-radius: 3px;

        &.add {
            transition: .5s;
            border-style: dashed;
            border-color: #b6b6b6;


            &:hover {
                border: 1px dashed #b6b6b6;
                color: #131313;
                cursor: pointer;
            }
        }

        &.field {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);

        }

        .options-input {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;

            .input-new-tag {
                width: auto;
            }

            .el-tag {
                height: 28px;
                display: flex;
                align-items: center;
            }
        }
    }

}
</style>