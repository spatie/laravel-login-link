import React, { FormEvent } from 'react';
import { router } from '@inertiajs/react'

interface LoginLinkProps {
    className?: string;
    email?: string | null;
    guard?: string | null;
    keyId?: string | null;
    label?: string;
    redirectUrl?: string | null;
    userAttributes?: Record<string, any> | null;
}

export default function LoginLink({
    className = 'underline',
    email = null,
    guard = null,
    keyId = null,
    label = 'Login',
    redirectUrl = null,
    userAttributes = null,
}: LoginLinkProps) {
    function submit(event: FormEvent) {
        event.preventDefault();
        router.post(route('loginLinkLogin'), {
            email: email,
            key: keyId,
            redirect_url: redirectUrl,
            guard: guard,
            user_attributes: userAttributes,
        });
    };

    return (
        <form onSubmit={submit}>
            <button className={className} type="submit">
                {label}
            </button>
        </form>
    );
};
