<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/main.css">
    <title>Add product</title>
</head>
<body>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <div id="app">
        <h3 class="ma-4">Add product</h3>
        <hr class="divider mb-8">
        <div class="container">
        <form @submit.prevent="saveProduct" name="product_form" id="product_form">
            <div class="position-right">
                <button type="submit" class="box-btn-tertiary mr-8">Save</button>
                <button id="delete-product-btn" @click="goBack" class="box-btn">Cancel</button>
            </div>
            <div class="mt-8">
                    <label for="name">Name:</label>
                    <input v-model="input.name" type="text" id="name" name="name" placeholder="Product name" > <br>
                    <p v-if="errors.name" class="input-error">{{errors.name}}</p>
                    <label for="price">Price</label>
                    <input v-model="input.price"  type="number" step="0.01" id="price" name="price" placeholder="Product price in $" > <br>
                    <p v-if="errors.price" class="input-error">{{errors.price}}</p>
                    <label for="productType">Product type:</label>
                    <select @change="setSku" v-model="input.productType"  name="product_type" id="productType" >
                        <option value="dvd">DVD</option>
                        <option value="book">Book</option>
                        <option value="furniture">Furniture</option>
                    </select> <br>
                    <p v-if="errors.productType" class="input-error">{{errors.productType}}</p>
                    <label for="sku">SKU:</label>
                    <input v-model="input.sku" type="text" id="sku" name="sku" placeholder="SKU" @keyup="skuUppercase" @focus="skuCaption = true" @blur="skuCaption = false"> <span v-if="skuCaption" class="caption">Auto generated based on product type and sequence</span> <br>
                    <p v-if="errors.sku" class="input-error">{{errors.sku}}</p>
                    <hr class="my-4">
                    <div>
                        <div id="DVD" v-if="input.productType == 'dvd'">
                            <h4>"DVD" Attributes</h4>
                            <p class="caption">Please provide size</p>
                            <label for="size">Size(MB)</label>
                            <input v-model="input.dvd.size" type="number" name="dvd_size" id="size" step="0.01">
                        </div>
                        <div id="Book" v-else-if="input.productType == 'book'">
                            <h4>"Book" Attributes</h4>
                            <p class="caption">Please provide weight</p>
                            <label for="weight">Weight(Kg)</label>
                            <input v-model="input.book.weight" type="number" name="book_weight" id="weight" step="0.01">
                        </div>
                        <div id="Furniture" v-else-if="input.productType == 'furniture'">
                            <h4>"Furniture" Attributes</h4>
                            <p class="caption">Please provide dimensions</p>
                            <label>Dimensions</label>
                            <input v-model="input.furniture.width" type="number" name="furniture_width" id="width" placeholder="Width" step="0.01">
                            <input v-model="input.furniture.height" type="number" name="furniture_height" id="height" placeholder="Height" step="0.01">
                            <input v-model="input.furniture.length" type="number" name="furniture_length" id="length" placeholder="Length" step="0.01">
                        </div>
                    </div>
                    <div v-if="saveError">
                        <hr class="my-4">
                        <p class="input-error">{{saveError}}</p>
                    </div>
            </div>
        </form>
        </div>
    </div>
    <script>
        const { createApp } = Vue
        createApp({
            data() {
                return {
                    lastId: `<?= $data['lastId']?>`,
                    input:{
                        sku: null,
                        name: null,
                        price:null,
                        productType: null,
                        dvd:{
                            size:null
                        },
                        book: {
                            weight:null
                        },
                        furniture: {
                            width:null,
                            height:null,
                            length:null
                        }
                    },
                    errors:{
                        sku: null,
                        name: null,
                        price: null,
                        productType:null
                    },
                    skuCaption:false,
                    skuPrefix: {
                        "dvd": "DV",
                        "furniture": "FN",
                        "book": "BK"
                    },
                    saveError: null
                }
            },
            watch:{
                'input.sku': function(newVal){
                    this.errors.sku = null;
                },
                'input.name': function(){
                    this.errors.name = null;
                },
                'input.price': function(){
                    this.errors.price = null;
                },
                'input.productType': function(){
                    this.errors.productType = null;
                }
            },
            methods:{
                saveProduct: async function(){
                    let success = this.validateInputs();
                    if(success){
                        let form = document.getElementById("product_form");
                        let response = await fetch("add-product/save",{
                            method: "POST",
                            body: new FormData(form)
                        });
                        let data = await response.json();
                        if(data.success){
                            this.goBack();
                        }else{
                            this.saveError = data.message;
                        }
                    }
                },
                validateInputs: function(){
                    let emptyError = "Please, submit required data";
                    let valid = true;
                    if(!this.input.sku || !this.input.name || !this.input.price || !this.input.productType) {
                        if(!this.input.sku) this.errors.sku = emptyError;
                        if(!this.input.name) this.errors.name = emptyError;
                        if(!this.input.price) this.errors.price = emptyError;
                        if(!this.input.productType) this.errors.productType = emptyError;
                        valid = false;
                    }
                    return valid;
                },
                goBack: function(){
                    document.location.href = '/';
                },
                skuUppercase: function(newVal){
                    this.input.sku = this.input.sku.toUpperCase();
                },
                setSku: function(){
                    this.input.sku = this.skuPrefix[this.input.productType] + (parseInt(this.lastId)+1+'').padStart(4,"0");
                }
            }
        }).mount("#app");
    </script>
</body>
</html>