import { createContext, useContext } from "react";

const ThemeContext = createContext({
    mode: "light",

    radius: "rounded-3xl",

    container: "max-w-7xl",
});

export function ThemeProvider({
    children,

    value = {},
}) {
    const defaults = {
        mode: "light",

        radius: "rounded-3xl",

        container: "max-w-7xl",
    };

    return (
        <ThemeContext.Provider
            value={{
                ...defaults,

                ...value,
            }}
        >
            {children}
        </ThemeContext.Provider>
    );
}

export function useTheme() {
    return useContext(ThemeContext);
}
