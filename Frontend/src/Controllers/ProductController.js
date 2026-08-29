import { ProductModel } from '../Models/ProductModel';

export class ProductController {
  constructor() {
    this.products = [];
    this.loading = false;
    this.error = null;
  }

  async fetchProducts(params = {}) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await ProductModel.getAll(params);
      this.products = response.data.map(ProductModel.formatProduct);
      return response;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async fetchProductById(id) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await ProductModel.getById(id);
      return ProductModel.formatProduct(response.data);
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async createProduct(productData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await ProductModel.create(productData);
      const newProduct = ProductModel.formatProduct(response.data);
      this.products.push(newProduct);
      return newProduct;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async updateProduct(id, productData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await ProductModel.update(id, productData);
      const updatedProduct = ProductModel.formatProduct(response.data);
      const index = this.products.findIndex(p => p.id === id);
      if (index !== -1) {
        this.products[index] = updatedProduct;
      }
      return updatedProduct;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async deleteProduct(id) {
    this.loading = true;
    this.error = null;
    
    try {
      await ProductModel.delete(id);
      this.products = this.products.filter(p => p.id !== id);
      return true;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  getProducts() {
    return this.products;
  }

  getActiveProducts() {
    return this.products.filter(p => p.isActive);
  }

  isInStock(productId) {
    const product = this.products.find(p => p.id === productId);
    return product ? product.stockQuantity > 0 : false;
  }

  isLoading() {
    return this.loading;
  }

  getError() {
    return this.error;
  }

  clearError() {
    this.error = null;
  }
}
