async function fetchJSON(url, options = {}) {
    const {
        timeout = 5000,
        retries = 3,
        retryDelay = 1000,
        ...fetchOptions
    } = options;

    // 创建超时控制
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    const fetchWithRetry = async (attempt = 1) => {
        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json',
                    ...fetchOptions.headers
                },
                ...fetchOptions
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();

        } catch (error) {
            clearTimeout(timeoutId);
            
            if (attempt < retries) {
                console.log(`请求失败，${retryDelay}ms后第${attempt + 1}次重试...`);
                await new Promise(resolve => setTimeout(resolve, retryDelay));
                return fetchWithRetry(attempt + 1);
            }
            
            throw error;
        }
    };

    return fetchWithRetry();
}