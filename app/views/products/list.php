<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/main.css">
    <title>Products list</title>
</head>
<body>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <div id="app">
        <h3 class="ma-4">Product list</h3>
        <hr class="divider mb-8">
        <div class="container">
            <div class="position-right">
                <button @click="goToAddPage" class="box-btn-primary mr-8">ADD</button>
                <button @click="massDelete" id="delete-product-btn" class="box-btn-red">MASS DELETE</button>
            </div>
            <div class="mt-8">
                <div class="card" v-for="product in products" :key="product.sku">
                    
                    <div class="card-content">
                        <div class="checkbox">
                            <input class="delete-checkbox" v-model="selectedProducts" type="checkbox" :value="product">
                        </div>
                        <h6 class="title">{{product.name}}</h6>
                        <h4 class="price">
                            {{product.price}}$
                        </h4>
                        <h4 class="attributes">
                            <hr class="my-4">
                            <div v-for="(attributeValue,key) in product.product_attributes" :key="key">
                                <span><b>{{key}}:</b> {{attributeValue}} {{ attributesSuffixes[key] }}</span>
                            </div>
                        </h4>
                        <div class="card-footer">
                            <div>
                                <span class="caption">SKU:{{product.sku}}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue
        createApp({
            data() {
                return {
                    products: [],
                    selectedProducts: [],
                    attributesSuffixes: {
                        "size": "MB",
                        "weight": "Kg",
                        "length": "cm",
                        "height": "cm",
                        "width": "cm"
                    }
                }
            },
            methods:{
                massDelete: async function(){
                    console.log(this.selectedProducts);
                    let formData = new FormData();
                    formData.set("ids", this.selectedProducts.map((p)=>p.id));
                    let response = await fetch("products/delete",{
                        method: "POST",
                        body: formData
                    });
                    let data = await response.json();
                    if(data.success){
                        for(let product of this.selectedProducts){
                            let productIndex = this.products.indexOf(product);
                            this.products.splice(productIndex,1);
                        }
                    }
                },
                goToAddPage: function(){
                    document.location.href = 'add-product';
                }
            },
            created(){
                let productsTxt = `<?php echo $data['products']; ?>`;
                let products = JSON.parse(productsTxt);
                this.products = products;
            }
        }).mount('#app')
    </script>
</body>
</html>