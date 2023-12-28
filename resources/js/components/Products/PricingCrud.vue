<template>
    <div class="pricing-crud">
        <div class="side left">
            <div class="header">
                <div class="title">PREÇO DE VENDA ATUAL</div>
                <small class="description">Política de precificação</small>
            </div>
            <div class="body">
                <div class="price">
                    <label @click.prevent="setValue('cost')" :class="{ 'hoverable': !only_view }">Preço de custo :<strong>{{
                        cost
                    }}</strong></label>
                    <label @click.prevent="setValue('markup')" :class="{ 'hoverable': !only_view }">Markup :<strong>{{
                        markup
                    }}</strong></label>
                    <label @click.prevent="setValue('comparing_price')" :class="{ 'hoverable': !only_view }">
                        Preço comparativo :<strong class="dashed">{{ comparingPrice }}</strong>
                    </label>
                    <label @click.prevent="setValue('price')" :class="{ 'hoverable': !only_view }">Preço :<strong>{{ price
                    }}</strong></label>
                </div>
            </div>
        </div>
        <div class="side right" v-if="hasHistory">
            <div class="header">
                <div class="title">ÚLTIMO PREÇO</div>
                <small class="description">Histórico de preços anteriormente configurados</small>
            </div>
            <div class="body">
                <div class="price">
                    <label>Preço de custo :<strong>{{ lastCost }}</strong></label>
                    <label>Markup :<strong>{{ markup }}</strong></label>
                    <label>Preço comparativo :<a href="#" class="dashed">{{ lastComparingPrice }}</a></label>
                    <label>Preço :<strong>{{ lastPrice }}</strong></label>
                    <a :href="`/admin/prices?product_code=${product_code}`">Ver histórico
                        completo</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['form', 'default_value', 'penultimate_price', 'product_code', 'only_view', 'history'],
    computed: {
        hasHistory() {
            return this.history ? (this.penultimate_price ? true : false) : false
        },
        fallback() {
            return (0).currency()
        },
        percentageFallback() {
            return ' ------- %'
        },
        comparingPrice() {
            return this.form?.comparing_price ? this.form.comparing_price.currency() : this.fallback
        },
        cost() {
            return this.form?.cost ? this.form.cost.currency() : this.fallback
        },
        markup() {
            return this.form?.markup ? +this.form.markup + " %" : this.percentageFallback
        },
        price() {
            return this.form?.price ? this.form.price.currency() : this.fallback
        },
        lastComparingPrice() {
            return this.penultimate_price ? this.penultimate_price.price.currency() : this.fallback
        },
        lastCost() {
            return this.penultimate_price ? this.penultimate_price.cost.currency() : this.fallback
        },
        lastMarkup() {
            return this.penultimate_price ? this.penultimate_price.markup.currency() : this.percentageFallback
        },
        lastPrice() {
            return this.penultimate_price ? this.penultimate_price.price.currency() : this.fallback
        }
    },
    watch: {
        "form.markup"(val) {
            this.ignoreWatch = true
            const cost = this.form.cost
            const point = cost * val / 100;
            const price = cost + point;
            this.$set(this.form, 'price', !isNaN(price) ? price.toFixed(2) : 0)
        },
        "form.price"(val) {
            const cost = this.form.cost
            const point = val - cost;
            const markup = point * 100 / cost;
            this.$set(this.form, 'markup', !isNaN(markup) ? markup.toFixed(2) : 0)
        },
        "form.cost"(val) {
            const markup = this.form.markup
            const point = val * markup / 100;
            const price = val + point;
            this.$set(this.form, 'price', !isNaN(price) ? price.toFixed(2) : 0)
        }
    },
    created() {
        this.init()
    },
    methods: {
        calcPrice() {
            const cost = this.form.cost
            const point = cost * this.form.markup / 100;
            const price = cost + point;
            this.$set(this.form, 'price', !isNaN(price) ? price.toFixed(2) : 0)
        },
        calcMarkup() {
            const cost = this.form.cost
            const point = this.form.price - cost;
            const markup = point * 100 / cost;
            this.$set(this.form, 'markup', !isNaN(markup) ? markup.toFixed(2) : 0)
        },
        init() {
            if (!this.form.cost) this.$set(this.form, 'cost', this.default_value?.cost || 0);
            if (!this.form.price) this.$set(this.form, 'price', this.default_value?.price || 0);
            if (!this.form.markup) this.$set(this.form, 'markup', this.default_value?.markup || 0);
            if (!this.form.comparing_price) this.$set(this.form, 'comparing_price', this.default_value?.compare_price || 0);
            this.calcMarkup()
        },
        setValue(key) {
            if (this.only_view) return;

            const texts = {
                cost: 'Defina o preço de custo em reais (R$)',
                markup: 'Defina o markup em porcentagem (%)',
                comparing_price: 'Define o preço comparativo em reais  (R$)',
                price: 'Defina o preço de venda em reais (R$)'
            }
            this.$prompt(texts[key], 'Precificação', {
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                inputType: 'number',
                inputPlaceholder: 'Digite o valor',
                inputPattern: /^\d+(\.\d{1,2})?$/,
                inputValue: this.form[key],
            }).then(({ value }) => {
                if (!isNaN(value)) {
                    const formated = (+value).toFixed(2)
                    this.$set(this.form, key, +formated)
                    setTimeout(() => {
                        const actions = {
                            cost: this.calcPrice,
                            markup: this.calcPrice,
                            price: this.calcMarkup
                        }
                        actions[key] && actions[key]()
                    });
                }
            })
        }

    }
}
</script>
<style lang="scss">
.pricing-crud {
    margin: 5px;
    display: grid;
    grid-template-columns: 1fr;
    border: 1px solid #cfc5c5;

    &:has(.side.right) {
        grid-template-columns: repeat(2, 1fr);
    }

    .side {
        display: grid;
        grid-template-rows: 50px auto;

        .header {
            border-bottom: 1px solid #cfc5c5;
            padding: 10px 15px;
            width: 100%;

            .title,
            .description {
                color: rgb(124, 124, 124);
                font-family: sans-serif;
                font-weight: 500;
                font-size: 11px;
            }

            .description {
                color: rgb(163, 163, 163);
                font-weight: 400;
                font-size: 10px;
                margin-top: 3px;
            }
        }

        &.left {
            background-color: #F3F5F8;
            border-right: 1px solid #cfc5c5;
        }

        &.right {
            background-color: #FFF4DA;
        }

        .body {
            display: grid;

            padding: 10px 15px;

            .price {
                display: flex;
                flex-direction: column;

                label {
                    font-family: sans-serif;
                    font-size: 14px;
                    color: #727272;
                    justify-content: flex-end;
                    display: flex;
                    transition: all .4s;

                    &.hoverable {
                        cursor: pointer;

                        &:hover {
                            filter: brightness(.7);
                        }
                    }

                    a,
                    strong {
                        margin-top: 0;
                        margin-left: 5px;
                        font-weight: 900;
                        color: #2563EB;
                    }
                }

                a {
                    margin-top: 10px;
                    font-size: 12px;
                    font-weight: 400;
                    color: #2563EB;
                }
            }
        }
    }

    @media screen and (max-width: 900px) {
        grid-template-columns: 1fr;

        .side {
            &.left {
                border-bottom: 1px solid #cfc5c5;
                border-right: unset;
            }
        }
    }

    .dashed {
        text-decoration: line-through;
        color: #924610 !important;
    }
}
</style>