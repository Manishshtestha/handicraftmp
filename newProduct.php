<div class="modal" id="addProduct">
    <div class="modal_content">
        <div class="cont">
            <div style="margin:5px 0;display:flex;justify-content:space-evenly;align-items:center;">
                <a class="Cancel" style="opacity:0">X</a>
                <h2 style="color:aliceblue;">Product Adding Form</h2>
                <a onclick="removeModal('product')" class="close">X</a>
            </div>
            <div class="form-content" style="border:1px solid grey; border-radius:10px;">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="top_ui">
                        <div class="inputs">
                            <input type="text" name="artisan_id" id="" value="<?= $_SESSION['user_id']?>" hidden>
                            <input type="text" name="availability" id="" value="1" hidden>
                            <label>
                                <span>Product Name</span>
                                <input type="text" name="product_name" />
                            </label>
                            <label>
                                <span>Price:</span>
                                <input type="number" name="price" min="0" step="0.01" />
                            </label>
                            <label>
                                <span>Description</span>
                                <textarea name="description" rows="2" id="descField"></textarea>
                            </label>
                            <label>
                                <span>Category:</span>
                                <select name="category">
                                    <option value="" disabled selected>--Select Category--</option>
                                    <option value="Textile and Fiber Arts">Textile and Fiber Arts</option>
                                    <option value="Home and Living">Home and Living</option>
                                    <option value="Craft Supplies">Craft Supplies</option>
                                    <option value="Glass Art">Glass Art</option>
                                    <option value="Painting and Drawing">Painting and Drawing</option>
                                    <option value="Sculpture">Sculpture</option>
                                    <option value="Seasonal Items">Seasonal Items</option>
                                </select>
                            </label>

                        </div>
                        <div class="images">
                            <div class="form-group">
                                <label for="mainImageInput"> <span>Product Image:</span>
                                    <div class="image-preview" id="mainImagePreview"><img src="./STATIC/Images/noImage.jpg" alt="" title="Click to Add Image"></div>
                                </label>
                                <input type="file" id="mainImageInput" name="main_img" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <button class="btn" name="addProduct" style="left:500px;">
                        <span class="button_top">
                            Add Product
                        </span>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
