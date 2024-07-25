<script setup>
    import { onMounted, ref } from 'vue';
    import { useRouter } from "vue-router";

    const router = useRouter();

    let invoices = ref([]);
    let search = ref([]);

    onMounted(async () => {
        getInvoices();
    });

    const getInvoices = async () => {
        let response = await axios.get('/api/invoices?s='+search.value);
        invoices.value = response.data.invoices;
    }

    const newInvoice = () => {
        router.push('/invoices/create');
    }

    const showInvoice = (id) => {
        router.push('/invoices/show/'+id);
    }

</script>

<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Invoices</h2>
                </div>
                <div>
                    <a class="btn btn-secondary" @click="newInvoice">
                        New Invoice
                    </a>
                </div>
            </div>

            <div class="table card__content">
                <div class="table--search">
                    <div class="table--search--wrapper">
                        <select class="table--search--select" name="" id="">
                            <option value="">Filter</option>
                        </select>
                    </div>
                    <div class="relative">
                        <i class="table--search--input--icon fas fa-search "></i>
                        <input v-model="search" @keyup="getInvoices()" class="table--search--input" type="text" placeholder="Search invoice">
                    </div>
                </div>

                <div class="table--heading">
                    <p>ID</p>
                    <p>Date</p>
                    <p>Number</p>
                    <p>Customer</p>
                    <p>Due Date</p>
                    <p>Sub Total</p>
                    <p>Discount</p>
                    <p>Total</p>
                </div>

                <!-- item 1 -->
                <div class="table--items" v-for="item in invoices" :key="item?.id" v-if="invoices.length > 0">
                    <a href="javascript:void(0)" @click="showInvoice(item?.id)" class="table--items--transactionId">{{item?.id}}</a>
                    <p>{{ item?.date }}</p>
                    <p>{{ item?.number }}</p>
                    <p v-if="item?.customer">{{ item?.customer?.first_name }}</p>
                    <p v-else>N/A</p>
                    <p>{{ item?.due_date }}</p>
                    <p> $ {{ item?.sub_total }}</p>
                    <p> $ {{ item?.discount }}</p>
                    <p> $ {{ item?.total }}</p>
                </div>
                <div class="table--items" v-else>
                    <p>Invoice Not Found</p>
                </div>
            </div>

        </div>
    </div>
</template>
