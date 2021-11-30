import * as React from 'react';
import { AddNewConfigToState } from '../config-form';
import registry from '../property-registry';
const __ = require('oro/translator');

const codeRegex = /^[a-z0-1_]+$/;
const initState = {
    code: '',
    type: '',
};

type ConfigCreate = {
    addNewConfig: AddNewConfigToState;
};

class ConfigSubjoin extends React.Component<ConfigCreate> {
    state = initState;

    render(): React.ReactNode {
        const baseId = 'new_config';

        return (
            <React.Fragment>
                <div className={'AknFieldContainer'} key={baseId + '_code_container'}>
                    <label htmlFor={baseId + '_code'}>Code</label>
                    <input
                        id={baseId + '_code'}
                        type={'text'}
                        value={this.state.code}
                        className={'AknTextField'}
                        onChange={(event: React.ChangeEvent<HTMLInputElement>): void => {
                            const state = this.state;
                            state.code = event.target.value;
                            this.setState(state);
                        }}
                    />
                </div>

                <div className={'AknFieldContainer'} key={baseId + '_type_container'}>
                    <label htmlFor={baseId + '_type'}>Type</label>
                    <select
                        id={baseId + '_type'}
                        value={this.state.type}
                        onChange={(event: React.ChangeEvent<HTMLSelectElement>): void => {
                            const state = this.state;
                            state.type = event.currentTarget.value;
                            this.setState(state);
                        }}
                    >
                        <option key={'config_option_default'} value={''}>
                            Select property type
                        </option>
                        {registry.getOptions().map((option) => {
                            return (
                                <option key={'config_option_' + option} value={option}>
                                    {__('flagbit_category.property_registry.option.' + option)}
                                </option>
                            );
                        })}
                    </select>
                </div>

                <div className={'AknFieldContainer'} key={baseId + '_button_container'}>
                    <button
                        className={'AknButton'}
                        onClick={(): void => {
                            if (!codeRegex.test(this.state.code) || this.state.type === '') {
                                return;
                            }

                            this.props.addNewConfig(this.state.code, this.state.type);
                            this.setState(initState);
                        }}
                    >
                        Append
                    </button>
                </div>
            </React.Fragment>
        );
    }
}

export default ConfigSubjoin;
