/**
 * Token管理工具类
 */
class TokenManager {
    static TOKEN_KEY = 'access_token';
    
    /**
     * 保存token到sessionStorage
     * @param {string} token 
     */
    static saveToken(token) {
        sessionStorage.setItem(this.TOKEN_KEY, token);
    }
    
    /**
     * 获取token
     * @returns {string|null}
     */
    static getToken() {
        return sessionStorage.getItem(this.TOKEN_KEY);
    }
    
    /**
     * 清除token
     */
    static clearToken() {
        sessionStorage.removeItem(this.TOKEN_KEY);
    }
    

    /**
     * 获取用于HTTP请求的Authorization header
     * @returns {object}
     */
    static getAuthHeaders() {
        const token = this.getToken();
        if (token) {
            return {
                'Authorization': 'Bearer ' + token
            };
        }
        return {};
    }
    
    /**
     * 重定向到登录页面
     * @param {string} loginUrl 
     */
    static redirectToLogin(loginUrl = '/json-auth/login.html') {
        this.clearToken();
        window.location.href = loginUrl;
    }
    
    /**
     * 检查token并在无效时重定向到登录页
     * @param {string} loginUrl 
     * @returns {boolean}
     */
    static requireAuth(loginUrl = '/json-auth/login.html') {
        if (!this.isTokenValid()) {
            this.redirectToLogin(loginUrl);
            return false;
        }
        return true;
    }
}

/**
 * API请求工具类
 */
class ApiClient {
    /**
     * 发送带认证的API请求
     * @param {string} url 
     * @param {object} options 
     * @returns {Promise}
     */
    static async request(url, options = {}) {
        const defaultHeaders = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...TokenManager.getAuthHeaders()
        };
        
        const config = {
            headers: defaultHeaders,
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {})
            }
        };
        
        console.log('Request URL:', url);
        console.log('Request Config:', config);
        try {
            const response = await fetch(url, config);
            
            // 如果是401未授权，清除token并重定向到登录页
            if (response.status === 401) {
                TokenManager.redirectToLogin();
                return;
            }
            
            return response;
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }
    
    /**
     * GET请求
     * @param {string} url 
     * @param {object} options 
     * @returns {Promise}
     */
    static get(url, options = {}) {
        return this.request(url, { method: 'GET', ...options });
    }
    
    /**
     * POST请求
     * @param {string} url 
     * @param {object} data 
     * @param {object} options 
     * @returns {Promise}
     */
    static post(url, data = null, options = {}) {
        const config = { method: 'POST', ...options };
        if (data) {
            config.body = JSON.stringify(data);
        }
        return this.request(url, config);
    }
    
    /**
     * PUT请求
     * @param {string} url 
     * @param {object} data 
     * @param {object} options 
     * @returns {Promise}
     */
    static put(url, data = null, options = {}) {
        const config = { method: 'PUT', ...options };
        if (data) {
            config.body = JSON.stringify(data);
        }
        return this.request(url, config);
    }
    
    /**
     * DELETE请求
     * @param {string} url 
     * @param {object} options 
     * @returns {Promise}
     */
    static delete(url, options = {}) {
        return this.request(url, { method: 'DELETE', ...options });
    }
}
